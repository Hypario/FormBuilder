<?php

namespace Form;

interface formBuilderInterface
{

    public function __construct(array $data = []);

    /**
     * Create one form
     * @param array $attributes
     * @return self
     */
    public function create(array $attributes = []);

    /**
     * Create an input field with the name, label and attributes given
     * @param string $name
     * @param null|string $label
     * @param array|null $attributes
     * @return self
     */
    public function input(string $name, ?string $label = '', ?array $attributes = []);

    /**
     * Create a password field with the name, label and attributes given
     * @param string $name
     * @param string $label
     * @param array|null $attributes
     * @return self
     */
    public function password(string $name = 'password', string $label = '', ?array $attributes = []);

    /**
     * Create a button of the type, text and classes of your choice
     * @param string $type
     * @param string $text
     * @param null|string $class
     * @return self
     */
    public function button(string $type, string $text, ?string $class = '');

    /**
     * set the classes of the inputs
     * @param string $inputClass
     * @return self
     */
    public function setInputClass(string $inputClass);

    /**
     * set the html surround of the inputs
     * @param string $html
     * @return self
     */
    public function setSurround(string $html);

    /**
     * Create a title for the form that will be shown before the form
     * @param string $html
     * @return self
     */
    public function title(string $html);
}