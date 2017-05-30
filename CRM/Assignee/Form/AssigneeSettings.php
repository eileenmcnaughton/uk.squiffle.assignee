<?php
/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 *
 * based on https://github.com/eileenmcnaughton/nz.co.fuzion.civixero/blob/master/CRM/Civixero/Form/XeroSettings.php
 */
class CRM_Assignee_Form_AssigneeSettings extends CRM_Core_Form {
  private $_settingFilter = array('group' => 'assignee');
  
  //everything from this line down is generic & can be re-used for a setting form in another extension
  //actually - I lied - I added a specific call in getFormSettings
  private $_submittedValues = array();
  private $_settings = array();

  function buildQuickForm() {
    $settings = $this->getFormSettings();
    foreach ($settings as $name => $setting) {
      if (isset($setting['quick_form_type'])) {
        $add = 'add' . $setting['quick_form_type'];
        if ($add == 'addElement') {
          $attribs = CRM_Utils_Array::value('html_attributes', $setting, array ());
          if (CRM_Utils_Array::value('options', $attribs) == 'GROUPS') {
            $attribs = array('' => ts('- select -')) + CRM_Core_PseudoConstant::group();
          }
          $this->$add($setting['html_type'], $name, ts($setting['title']), $attribs);
        }
        else {
          $this->$add($name, ts($setting['title']));
        }
        if ($setting['help_text']) {
          $this->assign("{$name}.help", $setting['help_text']);
        }
        $this->assign("{$setting['description']}_description", ts('description'));
      }
    }
    $this->addButtons(array(
      array (
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));
    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  function postProcess() {
    $this->_submittedValues = $this->exportValues();
    $this->saveSettings();
    parent::postProcess();
    CRM_Core_Session::setStatus(ts('Updates have been saved.'), ts('Saved'), 'success');
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons". These
    // items don't have labels. We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

  /**
   * Get the settings we are going to allow to be set on this form.
   *
   * @return array
   */
  function getFormSettings() {
    if (!isset(Civi::$statics[__CLASS__]['settings'])) {
      $res = civicrm_api3('Setting', 'getfields', array(
        'filters' => $this->_settingFilter,
      ));
      Civi::$statics[__CLASS__]['settings'] = $res['values']; 
    }
    return Civi::$statics[__CLASS__]['settings'];
  }

  /**
   * Get the settings we are going to allow to be set on this form.
   *
   * @return array
   */
  function saveSettings() {
    $settings = $this->getFormSettings();
    $values = array_intersect_key($this->_submittedValues, $settings);
    foreach ($values as $key => $value) {
      Civi::settings()->set($key, $value);
    }
  }

  /**
   * Set defaults for form.
   *
   * @see CRM_Core_Form::setDefaultValues()
   */
  function setDefaultValues() {
    $defaults = array();
    foreach (array_keys($this->getFormSettings()) as $setting) {
      $defaults[$setting] = Civi::settings()->get($setting);
    }
    return $defaults;
  }
}
