#include <arpa/inet.h>
#include <errno.h>
#include <libgen.h>
#include <mysql/mysql.h>
#include <netdb.h>
#include <netinet/in.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <unistd.h>

#include "protocol.h"
#include "serverFunction.h"
#define BUFF_SIZE 1024

extern MYSQL* con;

void finish_with_error(MYSQL* con) {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
}

void handle_message(char* message, int socket) {
    if (strlen(message) <= 0)
        return;
    char subtext[3];
    memcpy(subtext, &message[0], 2);
    subtext[2] = '\0';
    REQUEST_CODE type = atoi(subtext);
    //  char server_message[200] = "\0";
    printf("request: %s\n", subtext);
    switch (type)
    {
    case LOGIN: {
        printf("Handle login\n");
        loginUser(message, socket);
        break;
    }
    case REGISTER: {
        printf("Handle register\n");
        registerUser(message, socket);
        break;
    }
    case LOGOUT: {
        printf("Handle logout\n");
        logoutUser(message, socket);
        break;
    }
    case SHOW_LIST_PLACES: {
        printf("Handle list practices\n");
        showListPlaces(message, socket);
        break;
    }
    case SHOW_LIST_FAVORITE_PLACES: {
        printf("Handle favorite list practices\n");
        showListFavoritePlaces(message, socket);
        break;
    }
    case ADD_FAVARITE_PLACE: {
        printf("Handle add favorite place\n");
        addFavoritePlace(message, socket);
        break;
    }
    case SHOW_LIST_FRIEND: {
        printf("Handle list friend\n");
        showListFriend(message, socket);
        break;
    }
    case SHARE_PLACE: {
        printf("Handle share place\n");
        sharePlace(message, socket);
        break;
    }
    case SHOW_SHARED_PLACE: {
        printf("Show share place\n");
        showListSharedPlaces(message, socket);
        break;
    }
    default:
        break;
    }
}

int registerUser(char* message, int socket) {
    char username[255] = "\0";
    char password[255] = "\0";
    char serverMess[BUFF_SIZE] = "\0";
    char query[BUFF_SIZE] = "\0";
    char* token;

    // Split message to get username and password
    token = strtok(message, "|");
    token = strtok(NULL, "|");
    strcpy(username, token);
    token = strtok(NULL, "|");
    strcpy(password, token);

    // Check username is existed ?
    sprintf(query, "SELECT * FROM users WHERE username = '%s' ", username);
    if (mysql_query(con, query))
    {
        sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
        send(socket, serverMess, strlen(serverMess), 0);
        return 0;
    }
    MYSQL_RES* result = mysql_store_result(con);
    if (mysql_num_rows(result))
    {
        sprintf(serverMess, "%d|This username is existed|\n", REGISTER_USERNAME_EXISTED);
        send(socket, serverMess, strlen(serverMess), 0);
        return 0;
    }
    else
    {
        // Insert new account into database
        sprintf(query, "INSERT INTO users (username, password, status) VALUES ('%s', '%s', 0)", username, password);
        if (mysql_query(con, query))
        {
            sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
            send(socket, serverMess, strlen(serverMess), 0);
            return 0;
        }

        sprintf(serverMess, "%d|Successfully registered|\n", REGISTER_SUCCESS);
        send(socket, serverMess, sizeof(serverMess), 0);
        return 1;
    }
}

int loginUser(char* message, int socket) {
    printf("Start handle login\n");
    char username[255] = "\0";
    char password[255] = "\0";
    char serverMess[BUFF_SIZE] = "\0";
    char* token;
    char query[BUFF_SIZE] = "\0";
    // Split message to get username and password
    printf("message: %s\n", message);
    token = strtok(message, "|");
    token = strtok(NULL, "|");
    strcpy(username, token);
    token = strtok(NULL, "|");
    strcpy(password, token);

    printf("Username:%s , Password:%s\n", username, password);

    // Query to validate account
    // Check username
    sprintf(query, "SELECT * FROM users WHERE username = '%s'", username);
    printf("%s\n", query);
    if (mysql_query(con, query)) {
        sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
        printf("%s\n", serverMess);
        send(socket, serverMess, strlen(serverMess), 0);
        return 0;
    }

    MYSQL_RES* result = mysql_store_result(con);
    if (mysql_num_rows(result) == 0) {
        sprintf(serverMess, "%d|Invalid username|\n", USERNAME_NOT_FOUND);
        printf("%s\n", serverMess);
        send(socket, serverMess, strlen(serverMess), 0);
        return 0;
    }
    else {
        // Check password
        MYSQL_ROW row = mysql_fetch_row(result);
        if (strcmp(row[2], password)) {
            sprintf(serverMess, "%d|Password is incorrect|\n", PASSWORD_INCORRECT);
            printf("%s\n", serverMess);
            send(socket, serverMess, strlen(serverMess), 0);
            return 0;
        }
        else {
            // Check account is signing in other device
            char server_message[100] = "\0";
            char temp[512];
            if (atoi(row[3]) == 0) {
                char query1[BUFF_SIZE] = "\0";
                // Update status in users table
                sprintf(query1, "UPDATE users SET status = 1 WHERE username = '%s'", username);
                printf("%s\n", query1);
                if (mysql_query(con, query1)) {
                    sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
                    printf("%s\n", serverMess);
                    send(socket, serverMess, strlen(serverMess), 0);
                    return 0;
                }
                sprintf(server_message, "%d|%d|Successfully logged in|\n", LOGIN_SUCCESS, atoi(row[0]));
                printf("%s\n", serverMess);
                send(socket, server_message, sizeof(server_message), 0);
                return 1;
            }
            else {
                sprintf(server_message, "%d|Your account is signing in other device|\n", USERNAME_IS_SIGNIN);
                printf("%s\n", serverMess);
                send(socket, server_message, sizeof(server_message), 0);
                return 0;
            }
        }
    }
}

int logoutUser(char* message, int socket)
{
    printf("Start handle logout\n");
    char username[20] = "\0";
    char server_message[BUFF_SIZE] = "\0";
    char* token;
    char query[300] = "\0";

    // Split message to get username
    token = strtok(message, "|");
    token = strtok(NULL, "|");
    strcpy(username, token);

    // Update in database
    sprintf(query, "UPDATE users SET status = 0 WHERE username = '%s'", username);
    printf("%s\n", query);
    if (mysql_query(con, query))
    {
        sprintf(server_message, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
        send(socket, server_message, strlen(server_message), 0);
        return 0;
    }
    sprintf(server_message, "%d|\n", LOGOUT_SUCCESS);
    send(socket, server_message, strlen(server_message), 0);

    return 1;
}

void showListPlaces(char* message, int socket) {
    printf("Start send list places\n");
    int position;
    char temp[BUFF_SIZE];
    char serverMess[BUFF_SIZE] = "\0";
    char query[200] = "\0";
    char* token;
    char question[BUFF_SIZE];
    int level;

    // Get position
    printf("%s\n", message);
    token = strtok(message, "|");
    token = strtok(NULL, "|");
    strcpy(temp, token);
    position = atoi(temp);
    printf("Position %d\n", position);
    // Get position to choose appropriate question
    if (position == 0) {
        sprintf(query, "SELECT * FROM places");
        printf("%s\n", query);
        if (mysql_query(con, query)) {
            sprintf(serverMess, "%d|%s\n", QUERY_FAIL, mysql_error(con));
            send(socket, serverMess, strlen(serverMess), 0);
            return;
        }
        MYSQL_RES* result = mysql_store_result(con);
        sprintf(serverMess, "%d|%lld|\n", NUM_PLACES, mysql_num_rows(result));
        printf("Server message: %s\n", serverMess);
    }
    else {
        sprintf(query, "SELECT * FROM places WHERE id = %d", position);
        printf("%s\n", query);
        if (mysql_query(con, query)) {
            sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
            send(socket, serverMess, strlen(serverMess), 0);
            return;
        }
        MYSQL_RES* result = mysql_store_result(con);
        if (result == NULL) {
            finish_with_error(con);
        }
        MYSQL_ROW row;
        if ((row = mysql_fetch_row(result)) != NULL) {
            sprintf(serverMess, "%d|%s|%s|%s|%s|%s|\n", SHOW_PLACE, row[0], row[1], row[2], row[3], row[4]);
            printf("Server message: %s\n", serverMess);
        }
    }
    send(socket, serverMess, strlen(serverMess), 0);
    return;
}

void showListFavoritePlaces(char* message, int socket) {
    printf("Start send list favorite places\n");
    int position;
    int id_user;
    char temp[BUFF_SIZE];
    char temp1[BUFF_SIZE];
    char temp2[50] = "\0";
    char serverMess[BUFF_SIZE] = "\0";
    char query[200] = "\0";
    char* token;
    int level;

    // Get position
    printf("%s\n", message);
    token = strtok(message, "|");
    token = strtok(NULL, "|");
    strcpy(temp, token);
    id_user = atoi(temp);
    token = strtok(NULL, "|");
    printf("ID user: %d\n", id_user);
    // Get position to choose appropriate question
    sprintf(query, "SELECT * FROM favoriteplaces WHERE is_user = %d AND shared_by_id IS NULL", id_user);
    printf("%s\n", query);
    if (mysql_query(con, query)) {
        sprintf(serverMess, "%d|%s\n", QUERY_FAIL, mysql_error(con));
        send(socket, serverMess, strlen(serverMess), 0);
        return;
    }
    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL) {
        finish_with_error(con);
    }
    MYSQL_ROW row;
    while ((row = mysql_fetch_row(result)))
    {
        strcat(temp2, row[3]);
        strcat(temp2, "|");
    }
    sprintf(serverMess, "%d|%lld|%s\n", NUM_FAVORITE_PLACES, mysql_num_rows(result), temp2);
    printf("Server message: %s\n", serverMess);

    send(socket, serverMess, strlen(serverMess), 0);
    return;
}

void showListSharedPlaces(char* message, int socket) {
    printf("Start send list shared places\n");
    int position;
    int id_user;
    char temp[BUFF_SIZE];
    char temp1[BUFF_SIZE];
    char temp2[50] = "\0";
    char serverMess[BUFF_SIZE] = "\0";
    char query[200] = "\0";
    char* token;
    int level;

    // Get position
    printf("%s\n", message);
    token = strtok(message, "|");
    token = strtok(NULL, "|");
    strcpy(temp, token);
    id_user = atoi(temp);
    token = strtok(NULL, "|");
    printf("ID user: %d\n", id_user);
    // Get position to choose appropriate question
    sprintf(query, "SELECT * FROM favoriteplaces WHERE is_user = %d AND shared_by_id IS NOT NULL", id_user);
    printf("%s\n", query);
    if (mysql_query(con, query)) {
        sprintf(serverMess, "%d|%s\n", QUERY_FAIL, mysql_error(con));
        send(socket, serverMess, strlen(serverMess), 0);
        return;
    }
    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL) {
        finish_with_error(con);
    }
    MYSQL_ROW row;
    while ((row = mysql_fetch_row(result)))
    {
        strcat(temp2, row[2]);
        strcat(temp2, ",");
        strcat(temp2, row[3]);
        strcat(temp2, "|");
    }
    sprintf(serverMess, "%d|%lld|%s\n", NUM_SHARED_PLACE, mysql_num_rows(result), temp2);
    printf("Server message: %s\n", serverMess);

    send(socket, serverMess, strlen(serverMess), 0);
    return;
}

void addFavoritePlace(char* message, int socket) {
    printf("Start add favorite place\n");
    int position;
    char is_user[BUFF_SIZE];
    char id_place[BUFF_SIZE];
    char serverMess[BUFF_SIZE] = "\0";
    char query[200] = "\0";
    char query1[200] = "\0";
    char* token;

    // Get infor
    printf("message: %s\n", message);
    token = strtok(message, "|");
    token = strtok(NULL, "|");
    strcpy(is_user, token);
    token = strtok(NULL, "|");
    strcpy(id_place, token);

    sprintf(query, "SELECT * FROM favoriteplaces WHERE is_user = %d AND id_place = %d", atoi(is_user), atoi(id_place));
    printf("%s\n", query);
    if (mysql_query(con, query)) {
        sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
        send(socket, serverMess, strlen(serverMess), 0);
        return;
    }
    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL) {
        finish_with_error(con);
    }
    MYSQL_ROW row;
    if ((row = mysql_fetch_row(result)) == NULL) {
        sprintf(query1, "INSERT INTO favoriteplaces (is_user, id_place) VALUES (%d, %d);", atoi(is_user), atoi(id_place));
        if (mysql_query(con, query1)) {
            sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
            send(socket, serverMess, strlen(serverMess), 0);
            return;
        }
        sprintf(serverMess, "%d|Success!!!|\n", ADD_FAVARITE_PLACE_SUCCESS);
        send(socket, serverMess, strlen(serverMess), 0);
        printf("Server message: %s\n", serverMess);
        return;
    }
    else {
        sprintf(serverMess, "%d|Fail!!!|\n", ADD_FAVARITE_PLACE_FAIL);
        send(socket, serverMess, strlen(serverMess), 0);
        printf("Server message: %s\n", serverMess);
        return;
    }
}

void showListFriend(char* message, int socket) {
    printf("Start send list friends\n");
    int position;
    char temp[BUFF_SIZE];
    char serverMess[BUFF_SIZE] = "\0";
    char query[200] = "\0";
    char* token;
    char question[BUFF_SIZE];
    int level;

    // Get position
    printf("%s\n", message);
    token = strtok(message, "|");
    token = strtok(NULL, "|");
    strcpy(temp, token);
    position = atoi(temp);
    printf("Position %d\n", position);
    // Get position to choose appropriate question
    if (position == 0) {
        sprintf(query, "SELECT * FROM users");
        printf("%s\n", query);
        if (mysql_query(con, query)) {
            sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
            send(socket, serverMess, strlen(serverMess), 0);
            return;
        }
        MYSQL_RES* result = mysql_store_result(con);
        sprintf(serverMess, "%d|%lld|\n", NUM_FRIEND, mysql_num_rows(result));
        printf("Server message: %s\n", serverMess);
    }
    else {
        sprintf(query, "SELECT * FROM users WHERE id = %d", position);
        printf("%s\n", query);
        if (mysql_query(con, query)) {
            sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
            send(socket, serverMess, strlen(serverMess), 0);
            return;
        }
        MYSQL_RES* result = mysql_store_result(con);
        if (result == NULL) {
            finish_with_error(con);
        }
        MYSQL_ROW row;
        if ((row = mysql_fetch_row(result)) != NULL) {
            sprintf(serverMess, "%d|%s|%s|\n", SHOW_FRIEND, row[0], row[1]);
            printf("Server message: %s\n", serverMess);
        }
    }
    send(socket, serverMess, strlen(serverMess), 0);
}

void sharePlace(char* message, int socket) {
    printf("Start share place\n");
    int position;
    char is_user[BUFF_SIZE];
    char shared_by_id[BUFF_SIZE];
    char id_place[BUFF_SIZE];
    char serverMess[BUFF_SIZE] = "\0";
    char query[200] = "\0";
    char query1[200] = "\0";
    char* token;

    // Get infor
    printf("message: %s\n", message);
    token = strtok(message, "|");
    token = strtok(NULL, "|");
    strcpy(is_user, token);
    token = strtok(NULL, "|");
    strcpy(shared_by_id, token);
    token = strtok(NULL, "|");
    strcpy(id_place, token);

    sprintf(query, "SELECT * FROM favoriteplaces WHERE is_user = %d AND shared_by_id = %d AND id_place = %d", atoi(is_user), atoi(shared_by_id), atoi(id_place));
    printf("%s\n", query);
    if (mysql_query(con, query)) {
        sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
        send(socket, serverMess, strlen(serverMess), 0);
        return;
    }
    MYSQL_RES* result = mysql_store_result(con);
    if (result == NULL) {
        finish_with_error(con);
    }
    MYSQL_ROW row;
    if ((row = mysql_fetch_row(result)) == NULL) {
        sprintf(query1, "INSERT INTO favoriteplaces (is_user, shared_by_id, id_place) VALUES (%d, %d, %d);", atoi(is_user), atoi(shared_by_id), atoi(id_place));
        if (mysql_query(con, query1)) {
            sprintf(serverMess, "%d|%s|\n", QUERY_FAIL, mysql_error(con));
            send(socket, serverMess, strlen(serverMess), 0);
            return;
        }
        sprintf(serverMess, "%d|Success!!!|\n", SHARE_SUCCESS);
        send(socket, serverMess, strlen(serverMess), 0);
        printf("Server message: %s\n", serverMess);
        return;
    }
    else {
        sprintf(serverMess, "%d|Fail!!!|\n", SHARE_FAIL);
        send(socket, serverMess, strlen(serverMess), 0);
        printf("Server message: %s\n", serverMess);
        return;
    }
}

void encryptPassword(char* password) {
    for (int i = 0; i < strlen(password); i++)
    {
        if ((int)password[i] > i)
        {
            password[i] = password[i] - i;
        }
    }
}

