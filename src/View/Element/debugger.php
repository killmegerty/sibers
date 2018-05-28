<div class="debugger-container">
  <?= ' page: ' . $debugger->getPageLoadTime() . ' ms,' ?>
  <?= ' db: ' . $debugger->getDBReqCouner() . 'req (' . $debugger->getDBLoadTime() . 'ms),' ?>
  <?= ' cache: ' . $debugger->getMCReqCouner() . 'req (' . $debugger->getMCLoadTime() . 'ms)' ?>
</div>
