<?php

/**
 *  This file is part of SNEP.
 *
 *  SNEP is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  SNEP is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with SNEP.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * ContactGroups Controller
 *
 * @category  Snep
 * @package   Snep
 * @copyright Copyright (c) 2010 OpenS Tecnologia
 * @author    Rafael Pereira Bozzetti
 */

class ContactGroupsController extends Zend_Controller_Action {

    /**
     * List all Contact Groups
     */
    public function indexAction() {
       
        $this->view->breadcrumb = $this->view->translate("Cadastro » Grupos de Contato");

        $this->view->url = $this->getFrontController()->getBaseUrl() ."/". $this->getRequest()->getControllerName();
        
        $db = Zend_Registry::get('db');
        $select = $db->select()
                ->from("contacts_group");

        if( $this->_request->getPost('filtro') ) {
            $field = mysql_escape_string( $this->_request->getPost('campo') );
            $query = mysql_escape_string( $this->_request->getPost('filtro') );
            $select->where("`$field` like '%$query%'");
        }


        $page = $this->_request->getParam('page');
        $this->view->page = ( isset($page) && is_numeric($page)  ? $page : 1 );
        $this->view->filtro = $this->_request->getParam('filtro');
        
        $paginatorAdapter = new Zend_Paginator_Adapter_DbSelect($select);
        $paginator = new Zend_Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber( $this->view->page );
        $paginator->setItemCountPerPage(Zend_Registry::get('config')->ambiente->linelimit);

        $this->view->contactgroups = $paginator;
        $this->view->pages = $paginator->getPages();
        $this->view->PAGE_URL = "{$this->getFrontController()->getBaseUrl()}/{$this->getRequest()->getControllerName()}/index/";
        
        $opcoes = array("id"        => $this->view->translate("Código"),
                        "name"      => $this->view->translate("Nome") );

        $filter = new Snep_Form_Filter();
        $filter->setAction($this->getFrontController()->getBaseUrl() . '/' . $this->getRequest()->getControllerName() . '/index');
        $filter->setValue($this->_request->getPost('campo'));
        $filter->setFieldOptions($opcoes);
        $filter->setFieldValue($this->_request->getPost('filtro'));
        $filter->setResetUrl("{$this->getFrontController()->getBaseUrl()}/{$this->getRequest()->getControllerName()}/index/page/$page");

        $this->view->form_filter = $filter;
        $this->view->filter = array( array("url" => "{$this->getFrontController()->getBaseUrl()}/{$this->getRequest()->getControllerName()}/add/",
                                           "display" => $this->view->translate("Incluir Grupo de Contatos"),
                                           "css" => "include"),
                                   );
        
    }

    /**
     * Add a new Contact Group
     */
    public function addAction() {

        $this->view->breadcrumb = $this->view->translate("Grupos de Contatos » Cadastro");

        $db = Zend_Registry::get('db');

        $xml = new Zend_Config_Xml( "default/forms/contact_groups.xml" );

        $form = new Snep_Form( $xml );
        $form->setAction( $this->getFrontController()->getBaseUrl() .'/'. $this->getRequest()->getControllerName() . '/add');

        try {
            $sql = "SELECT c.id as id, c.name as name, g.name as `group` FROM contacts_names as c, contacts_group as g  WHERE (c.group = g.id) ";
            $contacts_result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        }catch(Exception $e) {
            display_error($LANG['error'].$e->getMessage(),true);
            
        }
        $contact = array();
        foreach($contacts_result as $key => $val) {
            $contact[$val['id']] = $val['name'] ." (". $val['group'] .")";
        }

        $this->view->objSelectBox = "contacts";
        $form->setSelectBox( $this->view->objSelectBox, $this->view->translate('Contatos'), $contact, $this->view->translate('Contatos do Grupo'));
        
        $form->setButtom();        

        if($this->_request->getPost()) {
                $form_isValid = $form->isValid($_POST);
                $dados = $this->_request->getParams();

                $groupId = Snep_ContactGroups_Manager::add(array('group' => $dados['group']));

                if( $dados['box_add'] ) {
                    foreach($dados['box_add'] as $id => $idContact) {
                        Snep_ContactGroups_Manager::insertContactOnGroup($groupId, $idContact);
                    }
                }
                $this->_redirect( $this->getRequest()->getControllerName() );
        }
        
        $this->view->form = $form;        

    }

    /**
     * Edit a Contact Group
     */
    public function editAction() {

        $this->view->breadcrumb = $this->view->translate("Grupos de Contatos » Alterar");

        $id = $this->_request->getParam('id');

        $db = Zend_Registry::get('db');
        $xml = new Zend_Config_Xml( "default/forms/contact_groups.xml" );

        $form = new Snep_Form( $xml );
        $form->setAction( $this->getFrontController()->getBaseUrl() .'/'. $this->getRequest()->getControllerName() . '/edit');

        $group = Snep_ContactGroups_Manager::get($id);
        $groupName = $form->getElement('group');
        $groupName->setValue($group['name']);        


        $groupContacts = array();
        foreach(Snep_ContactGroups_Manager::getGroupContacts($id) as $contact) {
            $groupContacts[$contact['id']] = "{$contact['name']} ({$contact['group']})";
        }

        $noGroupContacts = array();
        foreach(Snep_Contacts_Manager::getAll() as $contact) {
                if( ! isset($groupContacts[$contact['id']]) ) {
                    $noGroupContacts[$contact['id']] = "{$contact['name']} ({$contact['groupName']})";
                }
        }

        $this->view->objSelectBox = "contacts";
        $form->setSelectBox( $this->view->objSelectBox, $this->view->translate('Contatos'), $noGroupContacts, $this->view->translate('Contatos do Grupo'), $groupContacts);

        $hiddenId = new Zend_Form_Element_Hidden('id');
        $hiddenId->setValue($id);
        $form->addElement($hiddenId);

        $form->setButtom();

        if($this->_request->getPost()) {

                $form_isValid = $form->isValid($_POST);
                $dados = $this->_request->getParams();
                
                $groupId = Snep_ContactGroups_Manager::edit(array('group' => $dados['group'], 'id' => $dados['id']));

                if( $dados['box_add'] ) {
                    foreach($dados['box_add'] as $id => $idContact) {
                        Snep_ContactGroups_Manager::insertContactOnGroup($dados['id'], $idContact);
                    }
                }
                $this->_redirect( $this->getRequest()->getControllerName() );
        }

        $this->view->form = $form;           
    }

    /**
     * Remove a Contact Group
     */
    public function removeAction() {

        $this->view->breadcrumb = $this->view->translate("Grupos de Contatos » Remover");

        $id = $this->_request->getParam('id');
        $confirm = $this->_request->getParam('confirm');
        
        if( $confirm == 1 ) {
             Snep_ContactGroups_Manager::remove($id);
             $this->_redirect('default/contact-groups/');
        }

        $contacts = Snep_ContactGroups_Manager::getGroupContacts($id);

        if( count($contacts) > 0 )  {
            $this->_redirect('default/contact-groups/migration/id/'. $id);

        }else{
            
            $this->view->message = $this->view->translate("O Grupo de Contato será removido. Se estiver certo disto, clique em 'Confirmar'.");
            $form = new Snep_Form();
            $form->setAction( $this->getFrontController()->getBaseUrl() .'/'. $this->getRequest()->getControllerName() . '/remove/id/'.$id.'/confirm/1');

            $form->setButtom( $this->view->translate('Confirmar'));
            
            $this->view->form = $form;
        }
    }

    /**
     * Migrate contacts to other Contact Group
     */
    public function migrationAction() {

        $this->view->breadcrumb = $this->view->translate("Grupos de Contatos » Migrar contatos do grupo");

        $id = $this->_request->getParam('id');

        $_allGroups = Snep_ContactGroups_Manager::getAll();
        foreach($_allGroups as $group) {
            if($group['id'] != $id){
                $allGroups[$group['id']] = $group['name'];
            }
        }

        $form = new Snep_Form();
        $form->setAction( $this->getFrontController()->getBaseUrl() . '/contact-groups/migration/stage/2');

        if(isset($allGroups)) {
            $groupSelect = new Zend_Form_Element_Select('select');
            $groupSelect->setMultiOptions( $allGroups );
            $groupSelect->setLabel( $this->view->translate( $this->view->translate("Novo Grupo")  ) );
            $form->addElement($groupSelect);
            $form->addElement( new Zend_Form_Element_Submit( $this->view->translate("Migrar") ));
            $form->addElement( new Zend_Form_Element_Submit( $this->view->translate("Excluir") ));
            $this->view->message = $this->view->translate("O grupo excluído possue contatos associados a ele. Selecione um novo grupo para os contatos. ");

        }else{
            $groupName = new Zend_Form_Element_Text('new_group');
            $groupName->setLabel( $this->view->translate( $this->view->translate("Novo Grupo")  ) );
            $form->addElement($groupName);
            $form->addElement( new Zend_Form_Element_Submit( $this->view->translate("Criar") ));
            $form->addElement( new Zend_Form_Element_Submit( $this->view->translate("Excluir") ));
            $this->view->message = $this->view->translate("O grupo excluído é único e possue contatos associados a ele. Você pode migrar os contatos para um novo grupo. ");
        }

        $id_exclude = new Zend_Form_Element_Hidden("id");
        $id_exclude->setValue($id);

        $form->addElement($id_exclude);

        $stage = $this->_request->getParam('stage');

        if(isset($stage['stage']) && $id ) {

                if( isset( $_POST['select'] ) ) {
                    $toGroup = $_POST['select'];

                }else{
                    $new_group = array('group' => $_POST['new_group']);
                    $toGroup = Snep_ContactGroups_Manager::add( $new_group );
                }                

                $contacts = Snep_ContactGroups_Manager::getGroupContacts($id);
                foreach($contacts as $contact) {                    
                    Snep_ContactGroups_Manager::insertContactOnGroup($toGroup, $contact['id'] );
                }
                Snep_ContactGroups_Manager::remove($_POST['id']);
                
                $this->_redirect( $this->getRequest()->getControllerName() );                

        }
        
        $this->view->form = $form;
    }

}