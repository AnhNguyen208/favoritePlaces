<?php
    class Place {
        // Properties
        private $id;
        private $name;
        private $description;
        private $type;
        private $image;
        private $share_by;
        private $share_by_id;

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

        function set_share_by($share_by)
        {
            $this->share_by = $share_by;
        }
        function get_share_by()
        {
            return $this->share_by;
        }

         function set_share_by_id($share_by_id)
        {
            $this->share_by_id = $share_by_id;
        }
        function get_share_by_id()
        {
            return $this->share_by_id;
        }
    }
?>