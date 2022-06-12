<?php
$CI =& get_instance();
$CI->load->helper('general_helper');
$userdata = $CI->session->userdata();
foreach ($this->db->get('settings')->result() as $setting)
	$settings[$setting->key] = $setting->value;
//$name = '';
//$img = '';

if (isset($userdata) && isset($userdata['first_name']) && isset($userdata['last_name']) && isset($userdata['email'])) {
	//$name = $userdata['first_name'] . ' ' . $userdata['last_name'];
	// $img = 'https://www.gravatar.com/avatar/' . md5($userdata['email']);
}

?>

<div class="col-md-3 left_col menu_fixed">
    <div class="left_col scroll-view">
        <br>
        <div class="clearfix"></div>
        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="/assets/images/logo.png" width="233" height="64" align="middle"/>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3 class="color-text">Menu</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="<?php echo base_url('/dashboard') ?>"><i class="fa fa-dashboard color-text"></i><span
                                    class="color-text"> Tableau de Bord</span></a>
                    </li>
                    <li>
                        <a><i class="fa fa-users color-text"></i><span class="color-text"> Clients </span><span
                                    class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
							<?php if (can_list("Client")): ?>
                                <li><a href="<?php echo base_url('client/index/add') ?>"><span class="color-text">Ajouter Clients</span></a>
                                </li>
							<?php endif; ?>
							<?php if (can_list("Client")): ?>
                                <li><a href="<?php echo base_url('client/') ?>"><span
                                                class="color-text">Voir Clients</span></a></li>
							<?php endif; ?>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-home color-text"></i><span class="color-text"> Biens</span> <span
                                    class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
							<?php if (can_list("Projet")): ?>
                                <li><a href="<?php echo base_url('projet/') ?>"><span class="color-text">Projets</span></a>
                                </li>
							<?php endif; ?>
							<?php if (can_list("Blocs")): ?>
                                <li><a href="<?php echo base_url('blocs/') ?>"><span class="color-text">Blocs</span></a>
                                </li>
							<?php endif; ?>
							<?php if (can_list("Biens")): ?>
                                <li><a href="<?php echo base_url('biens/') ?>"><span class="color-text">Biens</span></a>
                                </li>
							<?php endif; ?>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-dollar color-text"></i> <span class="color-text">Ventes</span> <span
                                    class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
							<?php if (can_list("Vente")): ?>
                                <li><a href="<?php echo base_url('vente/') ?>"><span
                                                class="color-text">Ventes</span></a></li>
							<?php endif; ?>
							<?php if (can_list("Paiement")): ?>
                                <li><a href="<?php echo base_url('paiement/') ?>"><span
                                                class="color-text">Plan de Paiement</span></a></li>
							<?php endif; ?>
							<?php if (can_list("Echeance")): ?>
                                <li><a href="<?php echo base_url('echeance/') ?>"><span
                                                class="color-text">Echeances</span></a></li>
							<?php endif; ?>
							<?php if (can_list("Paiement")): ?>
                                <li><a href="<?php echo base_url('SuiviPaiement/') ?>"><span
                                                class="color-text">Suivi de Paiement</span></a></li>
							<?php endif; ?>
							<?php if (can_list("Paiement")): ?>
                                <li><a href="<?php echo base_url('SituationFinanciere/') ?>"><span
                                                class="color-text">Situation Financière</span></a></li>
							<?php endif; ?>
							<?php if (can_list("Comptes")): ?>
                                <li><a href="<?php echo base_url('comptes/') ?>"><span class="color-text">Comptes</span></a>
                                </li>
							<?php endif; ?>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-key color-text"></i> <span class="color-text">Authentification</span> <span
                                    class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
							<?php if (can_list("Utilisateurs")): ?>
                                <li><a href="<?php echo base_url('utilisateurs/') ?>"><span class="color-text">Utilisateurs</span></a>
                                </li>
							<?php endif; ?>
							<?php if (can_list("Roles")): ?>
                                <li><a href="<?php echo base_url('roles/') ?>"><span class="color-text">Roles</span></a>
                                </li>
							<?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Sidebar Menu -->
    </div>
</div>
