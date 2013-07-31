<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

//
// Require 3rd-party libraries here:
//
//require_once 'PHPUnit/Autoload.php';
//require_once 'PHPUnit/Framework/Assert/Functions.php';

//
use Behat\Behat\Context\Step\Given,
    Behat\Behat\Context\Step\Then,
    Behat\Behat\Context\Step\When;

/**
 * Features context.
 */
class FeatureContext extends MinkContext {

    protected $current_url;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array   $parameters     context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters) {
        // Initialize your context here
    }

    protected function getPage() {
        return $this->getSession()->getPage();
    }

    /**
     * @Then /^I remember the url$/
     */
    public function iRememberTheUrl() {
        $this->current_url = $this->getSession()->getCurrentUrl();
    }

    /**
     * @Then /^I should check if the url match$/
     */
    public function iShouldCheckIfTheUrlMatch() {
        $this->getPage()->findLink($this->current_url);
        unset($this->current_url);
    }

    /**
     * @Then /^I wait for select "([^"]*)" to load$/
     */
    public function iWaitForSelectToLoad($arg1) {
        $field = $this->getPage()->findField($arg1);
        $jquery_check = "jQuery('#" . $field->getAttribute('id') . " option').length > 0";
        $this->getSession()->wait(3000, $jquery_check);
    }

    /**
     * @Then /^I wait for "([^"]*)" seconds$/
     */
    public function iWaitForSeconds($arg1) {

        $this->getSession()->wait($arg1 * 1000);
    }

    /**
     * @Then /^I check the select "([^"]*)" element if contains the value "([^"]*)"$/
     */
    public function iCheckTheSelectElementIfContainsTheValue($selectLabel, $defaultValue) {
        $elementName = $this->getPage()->findField($selectLabel)->getAttribute("name");
        $optionElement = $this->getPage()->find('xpath', '//select[@name="' . $elementName . '"]/option[@selected]');

        if (strtolower($optionElement->getText()) != strtolower($defaultValue)) {
            throw new \Exception('select option has not found default value for ' . $locator);
        }
    }


}
