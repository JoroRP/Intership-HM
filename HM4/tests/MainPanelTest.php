<?php declare(strict_types=1);

namespace HM4\Tests;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use HM4\MainPanel;
use HM4\User;
use HM4\Subject;

class MainPanelTest extends TestCase
{
    private MainPanel $mainPanel;

    protected function setUp(): void
    {
        $this->mainPanel = $this->getMockBuilder(MainPanel::class)
            ->onlyMethods(['login', 'adminMenu', 'gradeStudent', 'viewGrades'])
            ->getMock();

        $this->mainPanel->Subjects[] = new Subject("Math");
        $this->mainPanel->Subjects[] = new Subject("Science");

        $teacher = new User("teacher1", "password", "teacher", "Teacher One");
        $student = new User("student1", "password", "student", "Student One");

        $this->mainPanel->Users[] = $teacher;
        $this->mainPanel->Users[] = $student;

        $this->mainPanel->Subjects[0]->setTeachers([$teacher]);
        $this->mainPanel->Subjects[0]->setStudents([$student]);
        $this->mainPanel->Subjects[1]->setStudents([$student]);
    }

    public function testLogin()
    {
        $this->mainPanel->expects($this->once())
            ->method('login')
            ->willReturnCallback(function () {
                echo "Invalid username or password. You have 2 tries left.\n";
                echo "Login successful. Welcome, Admin Administratov!\n";
            });

        $this->expectOutputString("Invalid username or password. You have 2 tries left.\nLogin successful. Welcome, Admin Administratov!\n");
        $this->mainPanel->login();
    }

    public function testAdminMenuCreateSubject()
    {
        $this->mainPanel->expects($this->once())
            ->method('adminMenu')
            ->willReturnCallback(function () {
                echo "Please enter a name for the subject - PE was created successfully.\n";
                $this->mainPanel->Subjects[] = new Subject("PE");
            });

        $this->expectOutputString("Please enter a name for the subject - PE was created successfully.\n");
        $this->mainPanel->adminMenu();
        $this->assertCount(3, $this->mainPanel->Subjects);
    }

    public function testAdminMenuCreateTeacher()
    {
        $this->mainPanel->expects($this->once())
            ->method('adminMenu')
            ->willReturnCallback(function () {
                echo "Please enter a username for the teacher - teacher2 created successfully.\n";
                $this->mainPanel->Users[] = new User("teacher2", "password", "teacher", "Teacher Two");
            });

        $this->expectOutputString("Please enter a username for the teacher - teacher2 created successfully.\n");
        $this->mainPanel->adminMenu();
        $this->assertCount(3, $this->mainPanel->Users);
    }

    public function testAdminMenuRemoveSubject()
    {
        $this->mainPanel->expects($this->once())
            ->method('adminMenu')
            ->willReturnCallback(function () {
                echo "Enter the name of the subject you want to remove - Science removed successfully.\n";
                array_pop($this->mainPanel->Subjects);
            });

        $this->expectOutputString("Enter the name of the subject you want to remove - Science removed successfully.\n");
        $this->mainPanel->adminMenu();
        $this->assertCount(1, $this->mainPanel->Subjects);
    }

    public function testTeacherMenuGradeStudent()
    {
        $this->mainPanel->expects($this->once())
            ->method('gradeStudent')
            ->willReturnCallback(function () {
                echo "Enter grade for Student One - Grade assigned successfully.\n";
                $this->mainPanel->Subjects[0]->setGrades(['student1' => '4']);
            });

        $this->expectOutputString("Enter grade for Student One - Grade assigned successfully.\n");
        $this->mainPanel->gradeStudent($this->mainPanel->Users[0]);
        $grades = $this->mainPanel->Subjects[0]->getGrades();
        $this->assertEquals('4', $grades['student1']);
    }

    public function testStudentMenuViewGrades()
    {
        $this->mainPanel->expects($this->once())
            ->method('viewGrades')
            ->willReturnCallback(function () {
                echo "Your grades, sorted by Subject: Math: 4, Science: No grade assigned.\n";
            });

        $this->expectOutputString("Your grades, sorted by Subject: Math: 4, Science: No grade assigned.\n");
        $this->mainPanel->viewGrades($this->mainPanel->Users[1]);
    }
}
