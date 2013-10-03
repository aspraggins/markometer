<?php
echo '<a href="/modals/help/' . $helphref . '" title="' . $helptitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;" class="btn-help"><span>Help?</span></a>';
?>