<?php
/**
 * @package      CrowdFunding
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * CrowdFunding is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class CrowdFundingViewCountries extends JViewLegacy {
    
    protected $items;
    protected $pagination;
    protected $state;
    
    protected $option;
    
    public function __construct($config) {
        parent::__construct($config);
        $this->option = JFactory::getApplication()->input->get("option");
    }
    
    public function display($tpl = null){
        
        $this->state      = $this->get('State');
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        
        // Add submenu
        CrowdFundingHelper::addSubmenu($this->getName());
        
        // Prepare sorting data
        $this->prepareSorting();
        
        // Prepare actions
        $this->addToolbar();
        $this->addSidebar();
        $this->setDocument();
        
        parent::display($tpl);
    }
    
    /**
     * Prepare sortable fields, sort values and filters.
     */
    protected function prepareSorting() {
    
        // Prepare filters
        $this->listOrder  = $this->escape($this->state->get('list.ordering'));
        $this->listDirn   = $this->escape($this->state->get('list.direction'));
        $this->saveOrder  = (strcmp($this->listOrder, 'a.ordering') != 0 ) ? false : true;
    
        if ($this->saveOrder) {
            $this->saveOrderingUrl = 'index.php?option='.$this->option.'&task='.$this->getName().'.saveOrderAjax&format=raw';
            JHtml::_('sortablelist.sortable', $this->getName().'List', 'adminForm', strtolower($this->listDirn), $this->saveOrderingUrl);
        }
    
        $this->sortFields = array(
            'a.name'    => JText::_('COM_CROWDFUNDING_NAME'),
            'a.code'    => JText::_('COM_CROWDFUNDING_COUNTRY_CODE'),
            'a.id'      => JText::_('JGRID_HEADING_ID')
        );
    
    }
    
    /**
     * Add a menu on the sidebar of page
     */
    protected function addSidebar() {
    
        $this->sidebar = JHtmlSidebar::render();
    
    }
    
    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar(){
        
        // Set toolbar items for the page
        JToolbarHelper::title(JText::_('COM_CROWDFUNDING_COUNTRIES_MANAGER'));
        JToolbarHelper::addNew('country.add');
        JToolbarHelper::editList('country.edit');
        JToolbarHelper::divider();
        
        // Add custom buttons
		$bar = JToolBar::getInstance('toolbar');
		
		// Import
		$link = JRoute::_('index.php?option=com_crowdfunding&view=import&type=countries');
		$bar->appendButton('Link', 'upload', JText::_("COM_CROWDFUNDING_IMPORT"), $link);
		
		// Export
		$link = JRoute::_('index.php?option=com_crowdfunding&task=export.download&format=raw&type=countries');
		$bar->appendButton('Link', 'download', JText::_("COM_CROWDFUNDING_EXPORT"), $link);
		
        JToolbarHelper::divider();
        JToolbarHelper::deleteList(JText::_("COM_CROWDFUNDING_DELETE_ITEMS_QUESTION"), "countries.delete");
        JToolbarHelper::divider();
        JToolbarHelper::custom('countries.backToDashboard', "dashboard", "", JText::_("COM_CROWDFUNDING_DASHBOARD"), false);
        
    }
    
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() {
	    
		$this->document->setTitle(JText::_('COM_CROWDFUNDING_COUNTRIES_MANAGER'));
		
		// Scripts
		JHtml::_('behavior.multiselect');
		JHtml::_('bootstrap.tooltip');
		
		JHtml::_('formbehavior.chosen', 'select');
		
		$this->document->addScript('../media/'.$this->option.'/js/admin/list.js');
		
	}

}