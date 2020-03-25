<?php

return [
  'assignee_group' => [
    'group_name' => 'Assignee Settings',
    'group' => 'assignee',
    'name' => 'assignee_group',
    'type' => 'Integer',
    'title' => 'Activity Assignee Group',
    'description' => 'Limit activity assignees to a specific Group?',
    'help_text' => 'When selecting assignees for an activity, limit the available individuals to those in the specified group',
    'html_type' => 'select',
    'html_attributes' => ['options' => 'GROUPS'],
    'quick_form_type' => 'Element',
  ],

  'assignee_as_source' => [
    'group_name' => 'Assignee Settings',
    'group' => 'assignee',
    'name' => 'assignee_as_source',
    'type' => 'Boolean',
    'default' => 0,
    'title' => 'Activity Assignee default user',
    'description' => 'Set the Activity Assignee to the current user?',
    'help_text' => 'The assignees box is usually blank. By enabling this setting, the current user will be added automatically as an Assignee.  The user can remove this if desired.',
    'html_type' => 'checkbox',
    'html_attributes' => '',
    'quick_form_type' => 'Element',
  ],
];
