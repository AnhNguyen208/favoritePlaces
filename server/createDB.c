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
  char* password = "dptk2008";

  if (mysql_real_connect(con, server, user, password, NULL, 0, NULL, 0) == NULL)
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
  // if (mysql_query(con, "DROP TABLE IF EXISTS users"))
  // {
  //   fprintf(stderr, "%s\n", mysql_error(con));
  //   mysql_close(con);
  //   exit(1);
  // }

  // if (mysql_query(con, "CREATE TABLE users(id INT PRIMARY KEY AUTO_INCREMENT, username VARCHAR(255) UNIQUE, password VARCHAR(255))"))
  // {
  //   fprintf(stderr, "%s\n", mysql_error(con));
  //   mysql_close(con);
  //   exit(1);
  // }
  // printf("%s\n", "Create table users succesfully ...");

  // ****CREATE USER QUESTION****
  if (mysql_query(con, "DROP TABLE IF EXISTS places"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  if (mysql_query(con,
    "CREATE TABLE places ( id INT NOT NULL AUTO_INCREMENT, name VARCHAR(45) NULL, type VARCHAR(45) NULL, image TEXT NULL, description TEXT NULL, PRIMARY KEY(id));"
  ))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  printf("%s\n", "Create table questions succesfully ...");

  if (mysql_query(con, "INSERT INTO places (name, type, image, description) "
    "VALUES ('name1', 'type1', 'assets/image/image1.jpg', 'description1'),"
    "('name2', 'type2', 'assets/image/image2.jpg', 'description2'),"
    "('name3', 'type3', 'assets/image/image3.jpg', 'description3'),"
    "('name4', 'type4', 'assets/image/image4.jpg', 'description4'),"
    "('name5', 'type5', 'assets/image/image5.jpg', 'description5');"))
  {
    fprintf(stderr, "%s\n", mysql_error(con));
    mysql_close(con);
    exit(1);
  }
  printf("%s\n", "Inser database succesfully ...");

  mysql_close(con);
  exit(0);
}