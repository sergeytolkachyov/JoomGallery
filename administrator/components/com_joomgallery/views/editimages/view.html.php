<?php
/******************************************************************************\
**   JoomGallery 3                                                            **
**   By: JoomGallery::ProjectTeam                                             **
**   Copyright (C) 2008 - 2021  JoomGallery::ProjectTeam                      **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                  **
**   Released under GNU GPL Public License                                    **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look             **
**   at administrator/components/com_joomgallery/LICENSE.TXT                  **
\******************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * HTML View class for the images edit view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewEditimages extends JoomGalleryView
{
  /**
   * HTML view display method
   *
   * @access  public
   * @param   string  $tpl  The name of the template file to parse
   * @return  void
   * @since   1.5.5
   */
  function display($tpl = null)
  {
    $items = $this->get('Images');

    $cids = JRequest::getVar('cid', array(), '', 'array');

    // Sanitize request inputs
    JArrayHelper::toInteger($cids, array($cids));
    
    $cids = implode(',', $cids);

    // Get the form and fill the fields
    $form = $this->get('Form');

    // Add checkbox to additional fields
    foreach($form->getFieldsets() as $name => $fieldSet)
    {
      if($name != '')
      {
        foreach($form->getFieldset($name) as $fieldalias => $field)
        {
          $form->setFieldAttribute($field->getAttribute('name'),'cbname','change[]',(string) $field->group);
          $form->setFieldAttribute($field->getAttribute('name'),'cbvalue',(string) $field->getAttribute('name'),(string) $field->group);
          $form->setFieldAttribute($field->getAttribute('name'),'type','cb'.(string) $field->getAttribute('type'),(string) $field->group);
        }
      }
    }


    // Set maximum allowed user count to switch from listbox to modal popup selection
    $form->setFieldAttribute('owner', 'useListboxMaxUserCount', $this->_config->get('jg_use_listbox_max_user_count'));

    // Bind the data to the form
    $form->bind($items[0]);

    // Set some form fields manually
    $form->setValue('txtclearhits', null, JText::_('COM_JOOMGALLERY_IMGMAN_CLEAR_HITS_FOR_ALL_IMAGES'));
    $form->setValue('txtclearvotes', null, JText::_('COM_JOOMGALLERY_IMGMAN_CLEAR_VOTES_FOR_ALL_IMAGES'));
    $form->setValue('txtcleardownloads', null, JText::_('COM_JOOMGALLERY_IMGMAN_CLEAR_DOWNLOADS_FOR_ALL_IMAGES'));


    $this->assignRef('items', $items);
    $this->assignRef('cids',  $cids);
    $this->assignRef('form',  $form);

    $this->addToolbar();
    parent::display($tpl);
  }

  /**
   * Add the page title and toolbar.
   *
   * @access  public
   * @return  void
   *
   * @since 2.0
   */
  function addToolbar()
  {
    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_IMGMAN_IMAGE_MANAGER').' :: '.JText::_('COM_JOOMGALLERY_FIELDSET_EDITIMAGES'), 'images');

    JToolbarHelper::apply('apply');
    JToolbarHelper::save('save');
    JToolbarHelper::cancel('cancel');
    JToolbarHelper::spacer();
  }
}
