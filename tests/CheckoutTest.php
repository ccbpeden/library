<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Checkout.php";

    $server = 'mysql:host=localhost:8889;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CheckoutTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Checkout::deleteAll();
        }

        function test_CheckoutSettersGettersConstructor()
        {
            $patron_id = 1;
            $copy_id = 1;
            $due_date = "02/12/2018";
            $return_date = "02/19/2018";
            $id = 1;

            $checkout = new checkout($patron_id, $copy_id, $due_date, $return_date, $id);

            $result= array($checkout->getPatron_id(), $checkout->getCopy_id(), $checkout->getDue_Date(), $checkout->getReturn_Date(), $checkout->getId());
            // Assert
            $this->assertEquals([$patron_id,$copy_id,$due_date,$return_date, $id], $result);
        }

        function test_Checkout_save_getall_deletall()
        {
            $patron_id = 1;
            $copy_id = 1;
            $due_date = "02/12/2018";
            $return_date = "02/19/2018";
            $id = 1;
            $checkout1 = new checkout($patron_id, $copy_id, $due_date, $return_date, $id);

            $patron_id2 = 2;
            $copy_id2 = 2;
            $due_date2 = "03/12/2018";
            $return_date2 = "03/19/2018";
            $id2 = 2;
            $checkout2 = new checkout($patron_id2, $copy_id2, $due_date2, $return_date2, $id2);

            $patron_id3 = 1;
            $copy_id3 = 1;
            $due_date3 = "04/12/2018";
            $return_date3 = "04/19/2018";
            $id3 = 3;
            $checkout3 = new checkout($patron_id3, $copy_id3, $due_date3, $return_date3, $id3);

            $checkout1->save();
            Checkout::deleteAll();
            $checkout2->save();
            $checkout3->save();

            $returned_checkouts = Checkout::getAll();

            $this->assertEquals([$checkout2, $checkout3], $returned_checkouts);
        }
        function test_update()
        {
            $patron_id = 1;
            $copy_id = 1;
            $due_date = "02/12/2018";
            $return_date = "02/19/2018";
            $id = 1;
            $checkout1 = new checkout($patron_id, $copy_id, $due_date, $return_date, $id);
            $checkout1->save();
            $result= $checkout1->update(2,2, '02/12/2019', '02/19/2019', 2);

            $this->assertEquals([2,2, '02/12/2019', '02/19/2019'], [$checkout1->getPatron_id(),$checkout1->getCopy_id(),$checkout1->getDue_Date(),$checkout1->getReturn_Date()]);
        }

        function test_find()
        {
            $patron_id = 1;
            $copy_id = 1;
            $due_date = "02/12/2018";
            $return_date = "02/19/2018";
            $id = 1;
            $checkout1 = new checkout($patron_id, $copy_id, $due_date, $return_date, $id);

            $patron_id2 = 2;
            $copy_id2 = 2;
            $due_date2 = "03/12/2018";
            $return_date2 = "03/19/2018";
            $id2 = 2;
            $checkout2 = new checkout($patron_id2, $copy_id2, $due_date2, $return_date2, $id2);

            $patron_id3 = 1;
            $copy_id3 = 1;
            $due_date3 = "04/12/2018";
            $return_date3 = "04/19/2018";
            $id3 = 3;
            $checkout3 = new checkout($patron_id3, $copy_id3, $due_date3, $return_date3, $id3);

            $checkout1->save();
            $checkout2->save();
            $checkout3->save();

            $result = Checkout::find($checkout1->getId());

            $this->assertEquals($result, $checkout1);
        }

        function test_delete()
        {
            $patron_id = 1;
            $copy_id = 1;
            $due_date = "02/12/2018";
            $return_date = "02/19/2018";
            $id = 1;
            $checkout1 = new checkout($patron_id, $copy_id, $due_date, $return_date, $id);

            $patron_id2 = 2;
            $copy_id2 = 2;
            $due_date2 = "03/12/2018";
            $return_date2 = "03/19/2018";
            $id2 = 2;
            $checkout2 = new checkout($patron_id2, $copy_id2, $due_date2, $return_date2, $id2);

            $checkout1->save();
            $checkout2->save();

            $checkout2->delete();

            $result=Checkout::getAll();

            $this->assertEquals([$checkout1], $result);
        }

        function test_getPatronName()
        {
            $patron1 = new Patron('John Smith');
            $patron1->save();
            $patron_id = $patron1->getId();
            $copy_id = 1;
            $due_date = "02/12/2018";
            $return_date = "02/19/2018";
            $id = 1;
            $checkout1 = new checkout($patron_id, $copy_id, $due_date, $return_date, $id);
            $checkout1->save();

            $result = $checkout1->getPatronName();

            $this->assertEquals($patron1->getName(), $result);
        }
    }
?>
