<?php
// create datagrid
$datagrid = new simbio_datagrid();

// table spec
$table_spec = '`koperasi` as k
RIGHT JOIN harian as h ON h.idkoperasi = k.idkoperasi
LEFT JOIN `tipe_koperasi` as tk ON k.jenis = tk.idtipe_koperasi';

/** table spec
$table_spec = '`koperasi` as k
LEFT JOIN `non_coa` as n ON n.idkoperasi = k.idkoperasi 
LEFT JOIN coa as c ON c.idkoperasi = k.idkoperasi
LEFT JOIN shu as s ON s.idkoperasi = k.idkoperasi 
LEFT JOIN periode as p ON n.idperiode = p.idperiode 
LEFT JOIN periode as p2 ON c.idperiode = p2.idperiode
LEFT JOIN periode as p3 ON s.idperiode = p3.idperiode 
LEFT JOIN `tipe_koperasi` as tk ON k.jenis = tk.idtipe_koperasi';

$datagrid->setSQLColumn('k.idkoperasi, p.periode as \'Periode\'',
	'k.propinsi as \'Propinsi\'',
	'format((sum(c.c2110)+sum(c.c2210)+sum(c.c3110)+sum(c.c3120))/1000,2) AS \'Simpanan&nbsp;*)\'',
	'format(sum(c.c1140)/1000,2) AS \'Pinjaman&nbsp;*)\'',
	'format(sum((c.c3110+c.c3120))/1000,2) AS \'Modal Dalam&nbsp;*)\'',
	'format(sum((c.c3130+c.c3140))/1000,2) AS \'Modal Luar&nbsp;*)\'',
	'format(sum(n.vol_usaha)/1000,2) AS \'Volume Usaha&nbsp;*)\'',
	'format(sum((c.c11+c.c12+c.c13+c.c14))/1000,2) AS \'Asset&nbsp;*)\'',
	'format(sum((c.c3170+c.c3180))/1000,2) AS \'SHU&nbsp;*)\'',
	'format(avg(n.sb_simpanan),2) AS \'Suku Bunga Simpanan (%)\'',
	'format(avg(n.sb_pinjaman),2) AS \'Suku Bunga Pinjaman (%)\'',
	'format(avg((n.piutangmacet/n.akumulasi_pinjaman))*100,2) AS \'NPL (%)\'');
**/

$datagrid->setSQLColumn(
    'MIN(TIMESTAMPDIFF(MONTH,h.periode,curdate())), k.idkoperasi, h.periode as \'Periode\'',
	'CONCAT(\'<a href="frontpage.php?pr=\',k.propinsi,\'">\',Propinsi,\'</a>\') AS \'Propinsi\'',
	'format(sum((h.h1)/1e6),2) AS \'Simpanan&nbsp;*)\'',
	'format(sum((h.h2)/1e6),2) AS \'Pinjaman&nbsp;*)\'',
	'format(sum((h.h3)/1e6),2) AS \'Modal Dalam&nbsp;*)\'',
	'format(sum((h.h4)/1e6),2) AS \'Modal Luar&nbsp;*)\'',
	'format(sum((h.h5)/1e6),2) AS \'Volume Usaha&nbsp;*)\'',
	'format(sum((h.h6)/1e6),2) AS \'Asset&nbsp;*)\'',
	'format(sum((h.h7)/1e6),2) AS \'SHU&nbsp;*)\'',
	'format(avg((h.h8)),2) AS \'Suku Bunga Simpanan (%)\'',
	'format(avg((h.h9)),2) AS \'Suku Bunga Pinjaman (%)\'',
	'format(avg((h.h10)),2) AS \'NPL (%)\'');

$datagrid->setSQLorder('k.propinsi ASC');
//$datagrid->setSQLcriteria('YEAR(p.finaldate) = 2010 or YEAR(p2.finaldate) = 2010 or YEAR(p3.finaldate) = 2010');
$datagrid->sql_group_by = 'k.propinsi';

// set table and table header attributes
$datagrid->table_attr = 'align="center" id="dataList" cellpadding="5" cellspacing="0"';
$datagrid->table_header_attr = 'style="font-weight: bold; color:rgb(255,255,255); background-color:cyan; vertical-align:middle;"';
$datagrid->debug = true;
$datagrid->invisible_fields = array(0,1,2);

// put the result into variables
$datagrid_result = $datagrid->createDataGrid($dbs, $table_spec, 10, false);

$m = $sysconf['bulan'][date('n')-1] . ' ' . date('Y');
$frontpage_content = '<h3>Laporan Bulanan Berdasar Propinsi ('. $m .')&nbsp;<a href="keterangan.php" target="blank" title="Penjelasan tabel"><img src="images/info.png" /></a></h3><p>';
$frontpage_content .= $datagrid_result . "*) dalam jutaan\n</p>";
