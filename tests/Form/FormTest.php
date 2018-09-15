<?php


namespace Test\Form;


use Form\FormBuilder;
use PHPUnit\Framework\TestCase;

class FormBuilderTest extends TestCase
{

    private function trim(string $string)
    {
        $lines = explode(PHP_EOL, $string);
        $lines = array_map('trim', $lines);
        return implode('', $lines);
    }

    private function assertSimilar(string $expected, string $actual)
    {
        $this->assertEquals($this->trim($expected), $this->trim($actual));
    }

    public function testCreate()
    {
        $form = new FormBuilder();
        $form->create();
        $this->assertSimilar('<form method="POST"></form>', (string)$form);

        $form = (new FormBuilder())->create()->input('username');
        $this->assertSimilar('
            <form method="POST">
                <input type="text" name="username" id="username">
            </form>', (string)$form);
    }

    public function testMultipleCreate()
    {
        $form = (new FormBuilder())
            ->create(['action' => 'login.php'])
            ->input('loginUsername')
            ->create(['action' => 'register.php'])
            ->input('registerUsername');
        $this->assertSimilar(
            '<form action="login.php" method="POST">
                <input type="text" name="loginUsername" id="loginUsername">
            </form>
            <form action="register.php" method="POST">
                <input type="text" name="registerUsername" id="registerUsername">
            </form>', (string)$form);
    }

    public function testInput()
    {
        $form = (new FormBuilder())->input('name', '', ['id' => 'test']);
        $this->assertSimilar('
			<form method="POST">
			  <input type="text" name="name" id="test">
			</form>
		', (string)$form);
    }

    public function testInputWithData()
    {
        $form = (new FormBuilder(['name' => 'Jean']))->input('name');
        $this->assertSimilar('
			<form method="POST">
			  <input type="text" name="name" value="Jean" id="name">
			</form>
		', (string)$form);

        $form = (new FormBuilder(['name' => 'Jean', 'ville' => 'Paris']))->input('name')->input('ville');
        $this->assertSimilar('
			<form method="POST">
				<input type="text" name="name" value="Jean" id="name">
				<input type="text" name="ville" value="Paris" id="ville">
			</form>
        ', (string)$form);
    }

    public function testPassword()
    {
        $form = new FormBuilder();
        $form->password('password');
        $this->assertSimilar('
			<form method="POST">
				<input type="password" name="password" id="password">
			</form>
		', (string)$form);

        $form = new FormBuilder(['password' => 'azeaze']);
        $form->password('password');
        $this->assertSimilar('
			<form method="POST">
				<input type="password" name="password" value="azeaze" id="password">
			</form>', (string)$form);
    }

    public function testButton()
    {
        $form = (new FormBuilder())->button('submit', 'Envoyer');
        $this->assertSimilar('
			<form method="POST">
				<button type="submit">Envoyer</button>
			</form>', (string)$form);
    }

    public function testSurround()
    {
        $form = (new FormBuilder())->create()
            ->setSurround('<div class="form-control"></div>')
            ->input('username');
        $this->assertSimilar('
        <form method="POST">
            <div class="form-control">
                <input type="text" name="username" id="username">
            </div>
        </form>', (string)$form);

        $form = (new FormBuilder())
            ->setSurround('<div class="form-control">')
            ->input('username');
        $this->assertSimilar('
			<form method="POST">
				<div class="form-control">
					<input type="text" name="username" id="username">
				</div>
			</form>',(string)$form
        );

        $form = (new FormBuilder())
            ->setSurround('<p class="title">')
            ->input('username');
        $this->assertSimilar('
			<form method="POST">
				<p class="title">
					<input type="text" name="username" id="username">
				</p>
			</form>',(string)$form
        );
    }

    public function testLabel()
    {
        $form = (new FormBuilder())->input('username', 'Votre pseudo :');

        $this->assertSimilar('
			<form method="POST">
				<label for="username">Votre pseudo :</label>
				<input type="text" name="username" id="username">
			</form>', (string)$form
        );
    }

    public function testAttributes()
    {
        $form = (new FormBuilder())
            ->input('username',
                '',
                ['id' => 'username']);
        $this->assertSimilar('
		<form method="POST">
			<input type="text" name="username" id="username">
		</form>', (string)$form);

        $form = (new FormBuilder())
            ->input('username');
        $this->assertSimilar('
		<form method="POST">
			<input type="text" name="username" id="username">
		</form>', (string)$form);

        $form = (new FormBuilder())
            ->input('username',
                '',
                [
                    'id' => 'test',
                    'class' => 'form-input',
                    'value' => 'aze'
                ]);
        $this->assertSimilar('
			<form method="POST">
				<input type="text" name="username" value="aze" id="test" class="form-input">
			</form>
			',(string)$form);
    }

    public function testFormBuilderTitle()
    {
        $form = (new FormBuilder())->input('username')->title('<h1>Votre pseudo :</h1>');
        $this->assertSimilar('
			<h1>Votre pseudo :</h1>
			<form method="POST">
				<input type="text" name="username" id="username">
			</form>
			',(string)$form
        );

        $form = (new FormBuilder())
            ->create()
            ->input('username', 'Votre pseudo :')
            ->password('password', 'Votre mot de passe :')
            ->title('<h1>Inscription</h1>');
        $this->assertSimilar('
                    <h1>Inscription</h1>
                    <form method="POST">
                        <label for="username">Votre pseudo :</label>
                        <input type="text" name="username" id="username">
                        <label for="password">Votre mot de passe :</label>
                        <input type="password" name="password" id="password">
                    </form>
                    ',
            (string)$form
        );
    }

}