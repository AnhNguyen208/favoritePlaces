//
// Created by hapq on 19/1/2023.
//
#ifndef NETWORKPROGRAMMING_PROTOCOL_H
#define NETWORKPROGRAMMING_PROTOCOL_H

typedef enum {
    LOGIN,
    REGISTER,
    LOGOUT,

    SHOW_LIST_PLACES,
    SHOW_LIST_FAVORITE_PLACES,
    ADD_FAVARITE_PLACE,
    SHOW_LIST_FRIEND,
    SHARE_PLACE,
    SHOW_SHARED_PLACE,
    ADD_PLACE,
    DELETE_FAVORITE_PLACE,
} REQUEST_CODE;


typedef enum {
    SHOW_LIST_FAIL,
    NUM_PLACES,
    SHOW_PLACE,

    QUERY_FAIL,

    USERNAME_NOT_FOUND,
    USERNAME_BLOCKED,
    USERNAME_IS_SIGNIN,

    PASSWORD_INCORRECT,

    LOGIN_SUCCESS,

    LOGOUT_SUCCESS,
    LOGOUT_FAIL,

    REGISTER_SUCCESS,
    REGISTER_USERNAME_EXISTED,

    ADD_FAVARITE_PLACE_SUCCESS,
    ADD_FAVARITE_PLACE_FAIL,

    NUM_FAVORITE_PLACES,
    SHOW_FAVORITE_PLACE,

    NUM_FRIEND,
    SHOW_FRIEND,

    SHARE_SUCCESS,
    SHARE_FAIL,

    NUM_SHARED_PLACE,
    SHOW_SHARED_PLACE_SUCCESS,

    ADD_PLACE_SUCCESS,
    ADD_PLACE_FAIL,

    DELETE_FAVORITE_PLACE_SUCCESS,
    DELETE_FAVORITE_PLACE_FAIL,
} RESPONSE_CODE;

#endif //NETWORKPROGRAMMING_PROTOCOL_H
