<?php
    Class Checkout
    {
        private $id;
        private $patron_id;
        private $copy_id;
        private $due_date;
        private $return_date;

        function __construct($patron_id, $copy_id, $due_date, $return_date, $id = null)
        {
            $this->setPatron_id($patron_id);
            $this->setCopy_id($copy_id);
            $this->setDue_Date($due_date);
            $this->setReturn_Date($return_date);
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

    	function getPatron_id()
        {
    		return $this->patron_id;
    	}

    	function setPatron_id($patron_id)
        {
    		$this->patron_id = $patron_id;
    	}

    	function getCopy_id()
        {
    		return $this->copy_id;
    	}

        function setCopy_id($copy_id)
        {
        $this->copy_id = $copy_id;
        }

        function getDue_Date()
        {
            return $this->due_date;
        }

        function setDue_Date($due_date)
        {
            $this->due_date = $due_date;
        }

        function getReturn_Date()
        {
            return $this->return_date;
        }

        function setReturn_Date($return_date)
        {
            $this->return_date = $return_date;
        }


        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (patron_id, copy_id, due_date, return_date) VALUES ({$this->getPatron_id()}, {$this->getCopy_id()},  '{$this->getDue_Date()}', '{$this->getReturn_Date()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkouts;");
            $all_checkouts = array();
            foreach($returned_checkouts as $checkout)
            {
                $patron_id = $checkout['patron_id'];
                $copy_id = $checkout['copy_id'];
                $due_date = $checkout['due_date'];
                $return_date = $checkout['return_date'];
                $id = $checkout['id'];
                $new_checkout = new Checkout($patron_id, $copy_id, $due_date, $return_date, $id);
                array_push($all_checkouts, $new_checkout);
            }
            return $all_checkouts;
        }

        function update($new_patron_id, $new_copy_id, $new_due_date, $new_return_date)
        {
            $GLOBALS ['DB']->exec("UPDATE checkouts SET patron_id = {$new_patron_id}, copy_id = {$new_copy_id}, due_date = {$new_due_date}, return_date = {$new_return_date} WHERE id = {$this->getId()};");
            $this->setPatron_id($new_patron_id);
            $this->setCopy_id($new_copy_id);
            $this->setDue_Date($new_due_date);
            $this->setReturn_Date($new_return_date);
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM checkouts;");
        }

        static function find($input_id)
        {
            $returned_checkouts = Checkout::getAll();
            foreach($returned_checkouts as $returned_checkout)
            {
                $returned_id = $returned_checkout->getId();
                if($returned_id == $input_id)
                {
                    return $returned_checkout;
                }
            }
        }
        static function findcopyid($input_id)
        {
            $returned_checkouts = Checkout::getAll();
            foreach($returned_checkouts as $returned_checkout)
            {
                $returned_id = $returned_checkout->getCopy_id();
                if($returned_id == $input_id)
                {
                    return $returned_checkout;
                }
            }
        }
        function getPatronName()
        {
            $all_patrons = Patron::getAll();
            foreach($all_patrons as $patron)
            {
                $patron_id = $patron->getId();
                if($patron_id = $this->getPatron_id())
                {
                    return $patron->getName();
                }
            }
        }

        function delete()
        {
            $GLOBALS['DB'] -> exec("DELETE FROM checkouts WHERE id = {$this->getId()};");
        }
    }
?>
