    <div class="user-info">
        <div class="image">
            <p id="aviator" data-letters="">
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['name']; ?></div>
                <div class="email"><?php  if(isset($_SESSION['email_id']) ){echo $_SESSION['email_id'];} ?></div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="../account/profile"><i class="material-icons">person</i>Profile</a></li>
                        <li><a href="../account/change_login"><i class="material-icons">person</i>Change Login</a></li>
                        <li><a href="../account/change_password"><i class="material-icons">vpn_key</i>Change Password</a></li>
                        <li role="seperator" class="divider"></li>
                        <li><a href="../dashboard/logout.php"><i class="material-icons">input</i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
