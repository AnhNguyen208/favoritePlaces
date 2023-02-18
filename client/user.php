<?php
    class User {
        private $id_user;
        private $username;

        function set_id_user($id_user) {
            $this->id_user = $id_user;
        }

        function get_id_user() {
            return $this->id_user;
        }

        function set_username($username) {
            $this->username = $username;
        }

        function get_username() {
            return $this->username;
        }
    }
?>