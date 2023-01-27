<?php
    class Place {
        // Properties
        public $id;
        public $name;
        public $description;
        public $type;
        public $image;

        // Methods
        function set_id($id)
        {
            $this->id = $id;
        }
        function get_id()
        {
            return $this->id;
        }

        function set_name($name)
        {
            $this->name = $name;
        }
        function get_name()
        {
            return $this->name;
        }
        function set_description($description)
        {
            $this->description = $description;
        }
        function get_description()
        {
            return $this->description;
        }

        function set_type($type)
        {
            $this->type = $type;
        }
        function get_type()
        {
            return $this->type;
        }

        function set_image($image)
        {
            $this->image = $image;
        }
        function get_image()
        {
            return $this->image;
        }
    }
?>