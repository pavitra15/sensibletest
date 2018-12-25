<ul class="list">
                    <li id="left_home">
                        <a href="../dashboard/index">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    
        <?php
            if(isset($_SESSION['d_id']))
            {
                echo'<li id="left_application">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings_applications</i>
                            <span>Application Setting</span>
                        </a>
                        <ul class="ml-menu">';
                if($_SESSION['device_type']=="Table")
                {
                    echo'<li>
                        <a href="../dashboard/premise">Premise Setting</a>
                    </li>
                    <li>
                        <a href="../dashboard/kitchen">Kitchen Setting</a>
                    </li>';
                }
                else
                {
                    echo'<li>
                        <a href="../dashboard/customer">Customer Type Setting</a>
                    </li>';
                }
                echo'<li>
                        <a href="../dashboard/waiter">'.$_SESSION['person_config'].'</a>
                </li>
                <li>
                    <a href="../dashboard/category">Category Setting</a>
                </li>
                <li>
                    <a href="../dashboard/tax">Tax</a>
                </li>
            </ul>
        </li>
                <li id="left_product">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">settings_applications</i>
                            <span>Product Setting</span>
                    </a>
                    <ul class="ml-menu">
                        <li>
                            <a href="../dashboard/product">Product</a>
                        </li>
                        <li>
                            <a href="../dashboard/stock">Stock</a>
                        </li>
                        <li>
                            <a href="../dashboard/price">Price</a>
                        </li>
                    </ul>
                </li>
                <li id="left_user">
                    <a href="../dashboard/user">
                        <i class="material-icons">verified_user</i>
                        <span>User Setting</span>
                    </a>
                </li>
                <li id="left_report">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">report</i>
                            <span>Report</span>
                    </a>
                    <ul class="ml-menu">
                        <li>
                            <a href="../report/stock_report">Stock Report</a>
                        </li>
                        <li>
                            <a href="../report/out_of_stock_report">Out Of Stock Report</a>
                        </li>
                         <li>
                            <a href="../report/reorder_stock__report">Reorder Product Report</a>
                        </li>
                        <li>
                            <a href="../report/bill_report">Bill Wise Report</a>
                        </li>';

                        if($_SESSION['device_type']=="Table")
                        {
                            echo'<li>
                                <a href="../report/kot_report">KOT Report</a>
                            </li>';
                        }
                        echo'<li>
                            <a href="../report/product_report">Product Wise Report</a>
                        </li>
                        <li>
                            <a href="../report/category_report">Category Wise Report</a>
                        </li>
                        <li>
                            <a href="../report/user_report">User Wise Bill Report</a>
                        </li>
                        <li>
                            <a href="../report/customer_report">Customer Report</a>
                        </li>
                        <li>
                            <a href="../report/credit_report">Credit Report</a>
                        </li>
                        <li>
                            <a href="../report/waiter_report">'.$_SESSION['person_config'].' Report</a>
                        </li>
                   </ul>
                </li>';
        }
        echo'<li id="left_register">
            <a href="../cumulative/configuration">
                <i class="material-icons">note_add</i>
                <span>Review Settings</span>
            </a>
        </li>
    </ul>';
    ?>