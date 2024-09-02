<?php declare(strict_types=1);

namespace HM6\Tests;

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use HM6\MainPanel;
use HM6\User;
use HM6\Subject;

class MainPanelTest extends TestCase
{
    private MainPanel $mainPanel;

    protected function setUp(): void
    {
        $this->mainPanel = new MainPanel();
        session_start();
    }

    public function testLoadUsersEmptyFile()
    {

        file_put_contents("users.txt", '');

        $this->mainPanel->loadUsers();

        $this->assertEmpty($this->mainPanel->Users);
    }

    public function testAddAdmin()
    {

        $this->mainPanel->Users = [];


        $this->mainPanel->addAdmin();

        $this->assertCount(1, $this->mainPanel->Users);
        $this->assertEquals('admin', $this->mainPanel->Users[0]->getUsername());
    }

    public function testAuthenticateUserSuccess()
    {

        $user = new User('john', 'password123', 'student', 'John Doe');
        $this->mainPanel->Users = [$user];

        $result = $this->mainPanel->authenticateUser('john', 'password123');

        $this->assertTrue($result);
    }

    public function testAuthenticateUserFail()
    {
        $user = new User('john', 'password123', 'student', 'John Doe');
        $this->mainPanel->Users = [$user];

        $result = $this->mainPanel->authenticateUser('john', 'wrongpassword');

        $this->assertFalse($result);
    }

    public function testCreateSubject()
    {
        $this->mainPanel->createSubject('Maths');

        $this->assertCount(1, $this->mainPanel->getSubjects());
        $this->assertEquals('Maths', $this->mainPanel->getSubjects()[0]->getName());
    }

    public function testRemoveSubjectSuccess()
    {
        $subject = new Subject('Physics');
        $this->mainPanel->Subjects = [$subject];

        $_POST['subject_name'] = 'Physics';

        $this->mainPanel->removeSubject();

        $this->assertCount(0, $this->mainPanel->getSubjects());
    }

    public function testRemoveSubjectNotFound()
    {
        $_POST['subject_name'] = 'Nonexistent';

        $this->mainPanel->removeSubject();

        $this->assertEmpty($this->mainPanel->getSubjects());
    }

    public function testGradeStudentSuccess()
    {
        $subject = new Subject('Maths');
        $student = new User('student1', 'pass', 'student', 'Student One');
        $subject->setStudents([$student]);
        $this->mainPanel->Subjects = [$subject];

        $this->mainPanel->gradeStudent('Maths', 'student1', 4.0);

        $grades = $subject->getGrades();

        $this->assertIsFloat($grades['student1'], 'The grade should be a float');
        $this->assertEquals(4.0, $grades['student1'], 'Grade should be 4.0');

        $this->assertEquals('<div class="alert alert-success">Grade has been added successfully.</div>', $_SESSION['admin_message']);
    }

    public function testGradeStudentNotFound()
    {
        $subject = new Subject('Maths');
        $this->mainPanel->Subjects = [$subject];

        $this->mainPanel->gradeStudent('Maths', 'nonexistent_student', 3.0);

        $this->assertEquals('<div class="alert alert-danger">Student is not assigned to the selected subject.</div>', $_SESSION['admin_message']);
    }

}
