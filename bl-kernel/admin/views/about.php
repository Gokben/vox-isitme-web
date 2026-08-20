<?php

echo Bootstrap::pageTitle(array('title'=>$L->g('About'), 'icon'=>'info-circle'));

echo '
<table class="table table-striped mt-3">
	<tbody>
';

echo '<tr>';
echo '<td>CMS sürümü</td>';
echo '<td>'.BLUDIT_VERSION.'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Sürüm kod adı</td>';
echo '<td>'.BLUDIT_CODENAME.'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Derleme numarası</td>';
echo '<td>'.BLUDIT_BUILD.'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Disk usage</td>';
echo '<td>'.Filesystem::bytesToHumanFileSize(Filesystem::getSize(PATH_ROOT)).'</td>';
echo '</tr>';

echo '
	</tbody>
</table>
';
