    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="left-side sidebar-offcanvas">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img style="display:block" src="<?=imagelink($this->session->userdata('photo'))?>" class="img-circle" alt="" />
                    </div>

                    <div class="pull-left info">
                        <?php
                            $name = $this->session->userdata("name");
                            if(strlen($name) > 18) {
                               $name = substr($name, 0,18);
                            }
                            echo "<p>".$name."</p>";
                        ?>
                        <a href="<?=base_url("profile/index")?>">
                            <i class="fa fa-hand-o-right color-green"></i>
                            <?=$this->session->userdata("usertype")?>
                        </a>
                    </div> 
                    <span class="clearfix"></span>
                 
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search in Menu..." id="searchField">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
         
                
                </div>
                <ul class="sidebar-menu">
                    <?php
                        if(customCompute($dbMenus)) {
                            $menuDesign = '';
                            display_menu($dbMenus, $menuDesign);
                            echo $menuDesign;
                        }
                    ?>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>


        <script>
        $(function () {
            $('#sidebar-form').on('submit', function (e) {
                e.preventDefault();
            });

            $('.sidebar-menu li.active').data('lte.pushmenu.active', true);

            $('#search-input').on('keyup', function () {
                var term = $('#search-input').val().trim();

                if (term.length === 0) {
                    $('.sidebar-menu li').each(function () {
                        $(this).show(0);
                        $(this).removeClass('active');
                        if ($(this).data('lte.pushmenu.active')) {
                            $(this).addClass('active');
                        }
                    });
                    return;
                }

                $('.sidebar-menu li').each(function () {
                    if ($(this).text().toLowerCase().indexOf(term.toLowerCase()) === -1) {
                        $(this).hide(0);
                        $(this).removeClass('pushmenu-search-found', false);

                        if ($(this).is('.treeview')) {
                            $(this).removeClass('active');
                        }
                    } else {
                        $(this).show(0);
                        $(this).addClass('pushmenu-search-found');

                        if ($(this).is('.treeview')) {
                            $(this).addClass('active');
                        }

                        var parent = $(this).parents('li').first();
                        if (parent.is('.treeview')) {
                            parent.show(0);
                        }
                    }

                    if ($(this).is('.header')) {
                        $(this).show();
                    }
                });

                $('.sidebar-menu li.pushmenu-search-found.treeview').each(function () {
                    $(this).find('.pushmenu-search-found').show(0);
                });
            });
        });
    </script>