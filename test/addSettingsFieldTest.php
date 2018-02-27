<?php

function esc_attr($attrs) {
    return $attrs;
}

function esc_html_e($text) {
    echo $text;
}

function get_option() {
    return [
        'url' => 'previous url'
    ];
}

function checked($value) {
    echo '';
}

class AddSettingsFieldTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultTemplate() {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'add-settings-field.php';
        $addSettingsField = $getAddSettingsField((object)[
            'tag' => 'tag',
            'views' => dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR
        ]);

        $addSettingsField([
            'id' => 'field',
            'description' => 'field description'
        ]);
        $this->expectOutputString(
'<label>
    <input id="tag_field" name="tag[field]" type="text" value="" />
    field description</label>
');
    }

    public function testUrlTemplate() {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'add-settings-field.php';
        $addSettingsField = $getAddSettingsField((object)[
            'tag' => 'tag',
            'views' => dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR
        ]);

        $addSettingsField([
            'id' => 'urlField',
            'type' => 'url',
            'default' => 'default value',
            'placeholder' => 'enter url',
            'description' => 'description for url'
        ]);
        $this->expectOutputString(
'<label>
    <input id="tag_urlField" name="tag[urlField]" type="url" value="default value" placeholder="enter url" />
    description for url</label>
');
    }

    public function testSecretTemplate() {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'add-settings-field.php';
        $addSettingsField = $getAddSettingsField((object)[
            'tag' => 'tag',
            'views' => dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR
        ]);

        $addSettingsField([
            'id' => 'secretField',
            'type' => 'secret',
            'default' => 'default value',
            'placeholder' => 'enter secret',
            'description' => 'description for secret'
        ]);
        $this->expectOutputString(
'<label>
    <input id="tag_secretField" name="tag[secretField]" type="secret" value="default value" placeholder="enter secret" />
    description for secret</label>
');
    }

    public function testCheckboxTemplate() {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'add-settings-field.php';
        $addSettingsField = $getAddSettingsField((object)[
            'tag' => 'tag',
            'views' => dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR
        ]);

        $addSettingsField([
            'id' => 'checkField',
            'type' => 'checkbox',
            'description' => 'description for checkbox'
        ]);
        $this->expectOutputString(
'<label>
    <input id="tag_checkField" name="tag[checkField]" type="checkbox" value="" />
    description for checkbox</label>
');
    }
}
