<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Book.php";
    // require_once "src/Task.php";
    $server = 'mysql:host=localhost:8889;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
        //   Student::deleteAll();
        //   Task::deleteAll();
        }
        function test_Book_setters_getters_constructor()
        {
            // Act
            $title = "Pilgrim's Progress";
            $id = 1;
            $book = new Book($title, $id);
            $actual_result = array($book->getTitle(), $book->getId());
            // Assert
                $this->assertEquals([$title, $id], $actual_result);
        }
        // function test_Student_save_getAll_deleteAll()
        // {
        //     // Arrange
        //     $book1 = new Student('John Smith', '2017-02-28');
        //     $student2 = new Student('Tom Smith', '2017-02-27');
        //     $student3 = new Student('Jane Smith', '2017-02-26');
        //     // Act
        //     $student1->save();
        //     Student::deleteAll();
        //     $student2->save();
        //     $student3->save();
        //     $all_students = Student::getAll();
        //     // Assert
        //     $this->assertEquals([$student2, $student3], $all_students);
        // }
        // function test_Student_update()
        // {
        //     // Arrange
        //     $student1 = new Student('Tom Smith', '2017-02-27');
        //     $student1->save();
        //     // Act
        //     $student1->update('Tom Smythe', '2017-01-27');
        //     $all_students = Student::getAll();
        //     // Assert
        //     $this->assertEquals(
        //         'Tom Smythe|2017-01-27',
        //         $all_students[0]->getTitle() . '|' . $all_students[0]->getDateOfEnrollment()
        //     );
        // }
        // function test_Student_find()
        // {
        //     // Arrange
        //     $student1 = new Student('John Smith', '2017-02-28');
        //     $student2 = new Student('Tom Smith', '2017-02-27');
        //     $student1->save();
        //     $student2->save();
        //     // Act
        //     $result = Student::find($student2->getId());
        //     // Assert
        //     $this->assertEquals($student2, $result);
        // }
        // function testDeleteStudent()
        // {
        //     //Arrange
        //     $student1 = new Student('John Smith', '2017-02-28');
        //     $student2 = new Student('Tom Smith', '2017-02-27');
        //     $student3 = new Student('Jane Smith', '2017-02-26');
        //     $student1->save();
        //     $student2->save();
        //     $student3->save();
        //     // Act
        //     $student2->delete();
        //     //Assert
        //     $this->assertEquals([$student1, $student3], Student::getAll());
        // }

    }
?>
