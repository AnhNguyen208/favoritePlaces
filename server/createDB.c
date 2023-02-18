#include <mysql/mysql.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int main(int argc, char const* argv[])
{
  MYSQL* con = mysql_init(NULL);

  if (con == NULL)
  {
    printf("%s\n", mysql_error(con));
    exit(1);
  }

  char* server = "127.0.0.1";
  char* user = "root";
  char* password = "";

  if (mysql_real_connect(con, server, user, password, NULL, 3308, NULL, 0) == NULL)
  {
    printf("%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  if (!mysql_set_character_set(con, "utf8"))
  {
    printf("New client character set: %s\n",
      mysql_character_set_name(con));
  }

  //****CREATE DATABASE*****
  if (mysql_query(con, "CREATE DATABASE IF NOT EXISTS favorite_places CHARACTER SET utf8 COLLATE utf8_unicode_ci"))
  {
    if (strcmp(mysql_error(con),
      "Can't create database 'favorite_places'; database exists") == 0)
    {
      printf("Database is exists");
    }
    else
    {
      fprintf(stderr, "%s\n", mysql_error(con));
      mysql_close(con);
      exit(1);
    }
  }
  printf("%s\n", "Create database succesfully ...");

  // ****SELECT DATABASE****
  if (mysql_query(con, "USE favorite_places"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  printf("%s\n", "Using database ...");

  // ****CREATE USER TABLE****
  if (mysql_query(con, "DROP TABLE IF EXISTS users"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }

  if (mysql_query(con, "CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(255) NULL, password VARCHAR(255) NULL, status INT, PRIMARY KEY(`id`));"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  printf("%s\n", "Create table users succesfully ...");

  if (mysql_query(con, "INSERT INTO users (username, password, status) "
    "VALUES ('username1', '123456', 0),"
    "('username2', '123456', 0);"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  printf("%s\n", "Insert database succesfully ...");

  // ****CREATE PLACE TABLE****
  if (mysql_query(con, "DROP TABLE IF EXISTS places"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  if (mysql_query(con,
    "CREATE TABLE places (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(45) NULL, type VARCHAR(45) NULL, image TEXT NULL, description TEXT NULL, PRIMARY KEY(id));"
  ))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  printf("%s\n", "Create table places succesfully ...");

  if (mysql_query(con, "INSERT INTO places (name, type, image, description) "
    "VALUES ('Hanoi Museum', 'museum', 'assets/image/museum1.jpg', 'description1'),"
    "('Hoa Lo Prison Museum', 'museum', 'assets/image/museum2.jpg', 'description2'),"
    "('Vietnam National Fine Arts Museum', 'museum', 'assets/image/museum3.jpg', 'description3'),"
    "('La Maison 1888', 'restaurant', 'assets/image/restaurant1.jpg', 'description4'),"
    "('L’Escale', 'restaurant', 'assets/image/restaurant2.jpg', 'description5'),"
    "('Jardin Des Sens', 'restaurant', 'assets/image/restaurant3.jpg', 'description6'),"
    "('La Terrasse', 'coffee shop', 'assets/image/coffeeShop1.jpg', 'description7'),"
    "('An Garden Cafe', 'coffee shop', 'assets/image/coffeeShop2.jpg', 'description8'),"
    "('Xofa Café & Bistro', 'coffee shop', 'assets/image/coffeeShop3.jpg', 'description9'),"
    "('Nê.Cocktailbar', 'pub', 'assets/image/pub1.jpg', 'description10'),"
    "('The Haflington ', 'pub', 'assets/image/pub2.jpg', 'description11'),"
    "('The Den Bar', 'pub', 'assets/image/pub3.jpg', 'description12');"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  printf("%s\n", "Insert database succesfully ...");

  // ****CREATE FAVORITE PLACE TABLE****
  if (mysql_query(con, "DROP TABLE IF EXISTS favoriteplaces"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  if (mysql_query(con, "CREATE TABLE favoriteplaces (`id` INT NOT NULL AUTO_INCREMENT, is_user INT NULL, shared_by_id INT NULL, id_place INT NULL, status INT, PRIMARY KEY(`id`));"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  printf("%s\n", "Create table favoriteplaces succesfully ...");

  // ****CREATE FRIEND TABLE****
  if (mysql_query(con, "DROP TABLE IF EXISTS friends"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  if (mysql_query(con, "CREATE TABLE friends (`id` INT NOT NULL AUTO_INCREMENT, user1 INT, user2 INT, status INT, PRIMARY KEY(`id`));"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  printf("%s\n", "Create table friends succesfully ...");

  if (mysql_query(con, "INSERT INTO friends (user1, user2, status) "
    "VALUES (1, 2, 1),"
    "(2, 1, 1);"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  printf("%s\n", "Insert database succesfully ...");

  mysql_close(con);
  exit(0);
}
