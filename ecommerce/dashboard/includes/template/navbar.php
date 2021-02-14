

<nav class="navbar navbar-default navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="dashboard.php"><?php echo lang('homepage') ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="categories.php"><?php echo lang('categories') ?></a></li>
        <li><a href="items.php?action=manage"><?php echo lang('items') ?></a></li>
        <li><a href="members.php?action=manage"><?php echo lang('members') ?></a></li>
        <li><a href="comments.php?action=manage"><?php echo lang('comments') ?></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">osama <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../../layout/index.php"><?php echo lang('visit') ?></a></li>
            <li><a href="members.php?action=edit&userid=<?php echo $_SESSION['ID']; ?>"><?php echo lang('edit') ?></a></li>
            <li><a href="#"><?php echo lang('setting') ?></a></li>
            <li><a href="logout.php"><?php echo lang('logout') ?></a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>