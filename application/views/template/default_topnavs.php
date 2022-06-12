<!-- Top Nav -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle"><a id="menu_toggle"><i class="fa fa-bars"></i></a></div>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">
                        <?php
                        $CI =& get_instance();
                        $userdata = $CI->session->userdata();
                        $name = '';
                        $img = '';
                        if (isset($userdata) && isset($userdata['first_name']) && isset($userdata['last_name']) && isset($userdata['email'])) {
                            $name = $userdata['first_name'] . ' ' . $userdata['last_name'];
                            $img = 'https://www.gravatar.com/avatar/' . md5($userdata['email']);
                        }
                        ?>
                        <img src="<?= $img ?>" alt="">
                        <?= $name ?> <span class="fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="<?= base_url('utilisateurs/index/edit/' . $userdata['user_id']) ?>">Profile</a></li>
                        <li><a href="<?= base_url('settings') ?>"><span>Settings</span></a></li>
                        <li><a href="<?= base_url('login/logout') ?>"><i class="fa fa-sign-out pull-right"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /Top Nav -->