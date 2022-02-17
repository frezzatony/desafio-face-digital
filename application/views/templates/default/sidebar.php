<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
   <!-- Brand Logo -->
   <a href="/" class="brand-link">
     <img src="/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
     <span class="brand-text font-weight-light">Gestão</span>
   </a>

   <!-- Sidebar -->
   <div class="sidebar">
     
     <!-- Sidebar Menu -->
     <nav class="mt-2">
       <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         <li class="nav-item">
           <a href="<?php echo BASE_URL ?>index.php/vendas" class="nav-link">
             <i class="far fa-circle nav-icon"></i>
             <p>
               Vendas
             </p>
           </a>
         </li>
         <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Indicadores de vendas
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo BASE_URL ?>index.php/vendas/indicadores/clientes" class="nav-link">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>Vendas por cliente</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo BASE_URL ?>index.php/vendas/indicadores/servicos" class="nav-link">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>Vendas por serviço</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo BASE_URL ?>index.php/vendas/indicadores/mensal" class="nav-link">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>Vendas mensais</p>
                </a>
              </li>
            </ul>
          </li>
       </ul>
     </nav>
     <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
 </aside>
