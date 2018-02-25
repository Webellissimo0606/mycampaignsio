<?php if($proposals['relation_type'] == 'customer'){if($proposals['customer']===NULL){$customer = $proposals['namesurname'];} else $customer = $proposals['customer'];} ?>
<?php if($proposals['relation_type'] == 'lead'){$customer = $proposals['leadname'];} ?>
<?php
$obj_pdf = new TCPDF( 'I', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true );
$obj_pdf->SetCreator( PDF_CREATOR );
$obj_pdf->SetTitle( $title );
$obj_pdf->SetPrintHeader(false);
$obj_pdf->SetPrintFooter(false);
$obj_pdf->setCellHeightRatio(2);
$obj_pdf->setFooterFont( Array( PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA ) );
$obj_pdf->SetDefaultMonospacedFont( 'ciuis' );
$obj_pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
$obj_pdf->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
$obj_pdf->SetAutoPageBreak( TRUE, PDF_MARGIN_BOTTOM );
$obj_pdf->SetFont( 'ciuis', '', 9 );
$obj_pdf->setFontSubsetting( false );
$obj_pdf->AddPage();
ob_start();
$table .= '<table width="100%">
  <tbody>
    <tr>
      <td><img src="'.base_url('uploads/ciuis_settings/'.$settings['logo'].'').'" alt=""></td>
      <td style="text-align:right;">
	  <span style="font-weight:bold; font-size:16px;">'.lang('proposalprefix').'-'.str_pad($proposals['id'], 6, '0', STR_PAD_LEFT).'</span><br>
	  <span>'.lang('date').': '.$proposals['date'].'</span><br>
	  <span>'.lang('opentill').': '.$proposals['opentill'].'</span>
	  </td>
    </tr>
  </tbody>
</table>';


$table .= '<table width="100%">
  <tbody>
    <tr>
      <td><div class="col-xs-5 invoice-person">
							<span class="name">' . $settings[ 'company' ] . '</span><br>
							<span>' . $settings[ 'address' ] . '</span><br>
							<span>' . $settings[ 'postcode' ] . '/ ' . $settings[ 'town' ] . '/' . $settings[ 'city' ] . ',' . $settings[ 'countryid' ] . '</span><br>
							<span><b>' . lang( 'phone' ) . ':</b> ' . $settings[ 'phone' ] . '</span><br>
							<span><b>' . lang( 'contactemail' ) . ':</b> ' . $settings[ 'email' ] . '</span><br>
						</div></td>
      <td style="text-align:right;"><div class="col-xs-5 invoice-person"><span class="name"><b>PROPOSAL TO</b></span><br>
							<span class="name">'.$customer.'</span><br>
							<span>' . $proposals[ 'toemail' ] . '</span><br>
							<span>' . $proposals[ 'toaddress' ] . '</span><br>
						</div>
						
						</td>
    </tr>
  </tbody>
</table>';
$table .= '<div>' . $proposals[ 'content' ] . '</div>';
$table .= '<br>';
$table .= '<table class="invoice-details">';
$table .= '<tr>
					<th style="font-weight:900;">' . lang( 'invoiceitemdescription' ) . '</th>
					<th style="font-weight:900;" class="amount">' . lang( 'quantity' ) . '</th>
					<th style="font-weight:900;" class="amount">' . lang( 'price' ) . '</th>
					<th style="font-weight:900;" class="amount">' . lang( 'discount' ) . '</th>
					<th style="font-weight:900;" class="amount">' . lang( 'vat' ) . '</th>
					<th style="text-align:right; font-weight:bold;" class="amount">' . lang( 'total' ) . '</th>
			</tr>';

foreach ( $proposalitems as $fu ) {
	if($fu['in[product_id]']===NULL){$fuitem =  $fu['name'];} else $fuitem = $fu['in[name]'];
	$table .= '<tr>';
	$table .= '<td style="border-bottom:1px solid #f0f0f0;" class="description"><b>' . $fuitem . '</b> (' . $fu[ 'in[description]' ] . ')</td>';
	$table .= '<td style="border-bottom:1px solid #f0f0f0;" class="amount">' . number_format( $fu[ 'in[amount]' ], 2, '.', ',' ) . '</td>';
	$table .= '<td style="border-bottom:1px solid #f0f0f0;" class="amount"><span class="money-area">' . number_format( $fu[ 'in[price]' ], 2, '.', ',' ) . '</span></td>';
	$table .= '<td style="border-bottom:1px solid #f0f0f0;" class="amount">' . $fu[ 'in[discount_rate]' ] . '%</td>';
	$table .= '<td style="border-bottom:1px solid #f0f0f0;" class="amount">' . number_format( $fu[ 'in[vat]' ], 2, ',', '.' ) . '%</td>';
	$table .= '<td style="border-bottom:1px solid #f0f0f0; text-align:right" class="amount"><span class="money-area">' . number_format( $fu[ 'in[total]' ], 2, '.', ',' ) . ' '.currency.'</span></td>';
	$table .= '</tr>';
}

$table .= '</table>';
$table .= '&nbsp;';
$table .= '<hr>';
$table .= '<br>';
$table .= '&nbsp;<br>';
$table .= '<table class="invoice-details">';
$table .= '<tr>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td style="font-weight:bold;">' . lang( 'subtotal' ) . '';
$table .= '</td>';
$table .= '<td  style="text-align: right"><span class="money-area">' . number_format( $proposals[ 'total_sub' ], 2, '.', ',' ) . ' '.currency.'</span>';
$table .= '</td>';
$table .= '</tr>';

$table .= '<tr>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td style="font-weight:bold;">' . lang( 'linediscount' ) . '';
$table .= '</td>';
$table .= '<td  style="text-align: right"><span class="money-area">' . number_format( $proposals[ 'total_discount' ], 2, '.', ',' ) . ' '.currency.'</span>';
$table .= '</td>';
$table .= '</tr>';

$table .= '<tr>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td style="font-weight:bold;">' . lang( 'grosstotal' ) . '';
$table .= '</td>';
$table .= '<td  style="text-align: right">' . $grosstotal = ( $proposals[ 'total_sub' ] - $proposals[ 'total_discount' ] ) . ' <span class="money-area">' . number_format( $grosstotal, 2, '.', ',' ) . ' '.currency.'</span>';
$table .= '</td>';
$table .= '</tr>';

$table .= '<tr>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td style="font-weight:bold;">' . lang( 'tax' ) . '';
$table .= '</td>';
$table .= '<td style="text-align: right"><span class="money-area">' . number_format( $proposals[ 'total_vat' ], 2, '.', ',' ) . ' '.currency.'</span>';
$table .= '</td>';
$table .= '</tr>';


$table .= '<tr style="padding:4px">';
$table .= '<td>';
$table .= '</td>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td>';
$table .= '</td>';
$table .= '<td style="font-weight:bold; font-size:16px;border-top:1px solid gray;">' . lang( 'total' ) . '';
$table .= '</td>';
$table .= '<td  style="text-align: right;border-top:1px solid gray; font-size:16px;"><span class="money-area">' . number_format( $proposals[ 'total' ], 2, '.', ',' ) . ' '.currency.'</span>';
$table .= '</td>';
$table .= '</tr>';
$table .= '</table>';

ob_end_clean();
$obj_pdf->writeHTML( $table, true, false, true, false, '' );
$obj_pdf->Output( ''.$title.'.pdf', 'D' );
?>