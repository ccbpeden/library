<?php
    Class Copy
    {
        private $id;
        private $book_id;
        private $status;

        function __construct($book_id, $status, $id = null)
        {
            $this->setBook_id($book_id);
            $this->setStatus($status);
            $this->setId($id);
        }

        function getId()
        {
            return $this->id;
        }

        function setId($id)
        {
            $this->id = $id;
        }

        function getBook_id()
        {
            return $this->book_id;
        }

        function setBook_id($book_id)
        {
            $this->book_id = $book_id;
        }
        function getStatus()
        {
            return $this->status;
        }

        function setStatus($status)
        {
            $this->status = $status;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO copies (book_id, status) VALUES ({$this->getBook_id()}, {$this->getStatus()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $all_copies = array();
            foreach($returned_copies as $returned_copy)
            {
                $book_id = $returned_copy['book_id'];
                $status = $returned_copy['status'];
                $id = $returned_copy['id'];
                $new_copy = new Copy($book_id, $status, $id);
                array_push($all_copies, $new_copy);
            }
            return $all_copies;
        }

        function update($new_book_id, $new_status)
        {
            $GLOBALS ['DB']->exec("UPDATE copies SET book_id = {$new_book_id}, status = {$new_status} WHERE id = {$this->getId()};");
            $this->setBook_id($new_book_id);
            $this->setStatus($new_status);
        }


        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies;");
        }

        static function find($input_id)
        {
            $returned_copies = Copy::getAll();
            foreach($returned_copies as $returned_copy)
            {
                $returned_id = $returned_copy->getId();
                if($returned_id == $input_id)
                {
                    return $returned_copy;
                }
            }
        }

        function delete()
        {
            $GLOBALS['DB'] -> exec("DELETE FROM copies WHERE id = {$this->getId()};");
        }




    }
?>
