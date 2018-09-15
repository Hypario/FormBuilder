<?php

namespace Form;

class FormBuilder implements FormBuilderInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Array of fields from the form.
     *
     * @var string[]
     */
    private $fields = [];

    /**
     * @var string
     */
    private $surround = '';

    /**
     * Define the classes for every input of the form.
     *
     * @var string
     */
    private $inputClass = '';

    /**
     * Define every / or 1 Form.
     *
     * @var array[]
     */
    private $form;

    private $formIndex = 0;

    private $created = false;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function __toString()
    {
        $html = '';
        if (null !== $this->form) {
            foreach ($this->form as $form) {
                if (isset($form['title'])) {
                    $html .= $form['title'];
                    unset($form['title']);
                }
                foreach ($form as $field) {
                    $html .= $field;
                }
                $html .= '</form>';
            }
        } else {
            foreach ($this->fields as $field) {
                $html .= $field;
            }
        }

        return $html;
    }

    /**
     * Create the form.
     *
     * @param array $attributes
     *
     * @return FormBuilder
     */
    public function create(array $attributes = []): self
    {
        $this->created = true;
        ++$this->formIndex;
        $defaultAttributes = [
            'action' => '',
            'method' => 'POST'
        ];
        $attributes = array_merge($defaultAttributes, $attributes);
        $this->form[$this->formIndex]['form'] = "<form {$this->getAttributes($attributes)}>";

        return $this;
    }

    /**
     * Create an input field with the name, label and attributes given.
     *
     * @param string      $name
     * @param null|string $label
     * @param array|null  $attributes
     *
     * @return FormBuilder
     */
    public function input(string $name, ?string $label = '', ?array $attributes = []): self
    {
        $this->isCreated();
        $defaultAttributes = [
            'name'  => $name,
            'value' => $this->getValue($name),
            'id'    => $name,
            'class' => $this->inputClass
        ];
        if (null !== $attributes) {
            $attributes = array_merge($defaultAttributes, $attributes);
        }
        if ('' !== $label) {
            $this->form[$this->formIndex][$name] = $this->surround("
                    <label for=\"{$attributes['id']}\">{$label}</label>
                    <input type=\"text\" {$this->getAttributes($attributes)}>
                ");
        } else {
            $this->form[$this->formIndex][$name] = $this->surround("
                <input type=\"text\" {$this->getAttributes($attributes)}>
            ");
        }

        return $this;
    }

    /**
     * Create a password field with the name, label and attributes given.
     *
     * @param string     $name
     * @param string     $label
     * @param array|null $attributes
     *
     * @return FormBuilder
     */
    public function password(string $name = 'password', string $label = '', ?array $attributes = []): self
    {
        $this->isCreated();
        $defaultAttributes = [
            'name'  => $name,
            'value' => $this->getValue($name),
            'id'    => $name,
            'class' => $this->inputClass
        ];
        if (null !== $attributes) {
            $attributes = array_merge($defaultAttributes, $attributes);
        }
        if ('' !== $label) {
            $this->form[$this->formIndex][$name] = $this->surround("
                    <label for=\"{$attributes['id']}\">{$label}</label>
                    <input type=\"password\" {$this->getAttributes($attributes)}>
                ");
        } else {
            $this->form[$this->formIndex][$name] = $this->surround("
                <input type=\"password\" {$this->getAttributes($attributes)}>
            ");
        }

        return $this;
    }

    /**
     * Create a button of the type, text and classes of your choice.
     *
     * @param string      $type
     * @param string      $text
     * @param null|string $class
     *
     * @return FormBuilder
     */
    public function button(string $type, string $text, ?string $class = ''): self
    {
        $this->isCreated();
        $attributes = [
            'type'  => $type,
            'class' => $class
        ];
        $this->form[$this->formIndex][$type] = "<button {$this->getAttributes($attributes)}>{$text}</button>";

        return $this;
    }

    /**
     * set the classes of the inputs.
     *
     * @param string $inputClass
     *
     * @return FormBuilder
     */
    public function setInputClass(string $inputClass): self
    {
        $this->inputClass = $inputClass;

        return $this;
    }

    /**
     * set the html surround of the inputs.
     *
     * @param string $html
     *
     * @return FormBuilder
     */
    public function setSurround(string $html): self
    {
        $this->surround = $html;

        return $this;
    }

    /**
     * Create a title for the form that will be shown before the form.
     *
     * @param string $html
     *
     * @return FormBuilder
     */
    public function title(string $html): self
    {
        $this->form[$this->formIndex]['title'] = $html;

        return $this;
    }

    /**
     * return the value of the given name's field.
     *
     * @param string $key
     *
     * @return null|string
     */
    protected function getValue(string $key): ?string
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Get the attributes given and return them into a string
     * like src="http://www.example.com/" style="display: inline-block;".
     *
     * @param array $attributes
     *
     * @return string*
     */
    private function getAttributes(array $attributes): string
    {
        $htmlParts = [];
        foreach ($attributes as $key => $value) {
            if ('' !== $value && null !== $value) {
                $htmlParts[] = "{$key}=\"{$value}\"";
            }
        }

        return implode(' ', $htmlParts);
    }

    /**
     * @param string $html
     *
     * @return string
     */
    private function surround(string $html): ?string
    {
        if ('' !== $this->surround && isset($this->surround)) {
            $balise = explode('><', $this->surround);
            $balise = array_map(function ($balise) {
                return trim($balise, '<>');
            }, $balise);
            if (!array_key_exists(1, $balise)) {
                $parts = explode(' ', $balise[0]);
                $balise[1] = $parts[0];
                $html = "<$balise[0]>$html</{$balise[1]}>";
            } else {
                $html = "<$balise[0]>$html<{$balise[1]}>";
            }
        }

        return $html;
    }

    /**
     * Vérifie si le formulaire a bien été crée, si non il est créée.
     */
    private function isCreated(): void
    {
        if (!$this->created) {
            $this->create();
        }
    }
}
