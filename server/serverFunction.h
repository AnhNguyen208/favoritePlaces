#ifndef _SERVER_FUNC_H
#define _SERVER_FUNC_H
#include "protocol.h"

void handle_message(char*, int);
int registerUser(char*, int);
int loginUser(char*, int);
int logoutUser(char*, int);
void showListPlaces(char*, int);
void showListFavoritePlaces(char*, int);
void addFavoritePlace(char*, int);
void getListUser(char*, int);
void sharePlace(char*, int);
void showListSharedPlaces(char*, int);
void showListFriend(char*, int);
void finish_with_error(MYSQL* con);
void encryptPassword(char*);

#endif  // _SERVER_FUNC_H
