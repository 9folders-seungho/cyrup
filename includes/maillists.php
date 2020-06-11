<?php

  defined("INCLUDE_DIR") || exit("Not for direct run");

  print_header( TITLE."Maillists" );
  print_top_menu();
  print '<script type="text/javascript" src="'.JS_URL.'/functions.js" language="JavaScript"></script>'."\n";
    
  $order_by = get_order_by("maillists_order_by");
  $domain_id = get_domain_id();

  if ( isset($_POST['confirm']) && isset($_POST['action']) && $domain_id && ($sel = chks2sql()) ) {
    sql_query( "DELETE FROM cyrup_maillists WHERE ".$sel );
  }
    
  print_domain_selection( $domain_id );

  if ( $domain_id ) {
    print "<form name=form method=POST action='?admin&m=maillists'>\n";
    print "<input type=hidden name=action value=''>\n";
    print "<table width=100% border=0 cellpadding=0 cellspacing=0>\n";
    dotline( 4 );

    print "<tr>\n";
    print "<th width=1><input type=checkbox name=chkChangeAll onClick='check_boxes()'></th>\n";
    html_th( "alias", "Maillist alias" );
    html_th( "enabled", "Active", "Maillist active?" );
    html_th( "aliased_to", "Maillist members", "Members of maillists are listed here" );
    print "</tr>\n";

    dotline( 4 );
    print "<tr class=highlight><td colspan=5 align=center><a href='?admin&m=maillistform' class=button>[ Add new ]</a></td></tr>\n";
    dotline( 4 );
    
    $query = "SELECT * FROM cyrup_maillists WHERE domain_id=${domain_id} ".filter2sql('alias')." ORDER BY ${order_by}";
    sql_query($query);

    while ( $row = sql_fetch_array() ) {
            $aliased_to = explode( ",", $row['aliased_to'] );
            sort( $aliased_to );
            print "<td width=1 valign='top'><input type=checkbox name='ids[{$row['id']}]' value='".$row['id']."'></td>\n";
            print "<td valign='top'>&nbsp;<a href='?admin&m=maillistform&id=".$row['id']."'>".$row['alias']."</a></td>\n";
            print "<td valign='top' align=center>&nbsp;".( $row['enabled'] == 1 ? "Y" : "N")."</td>\n";
            print "<td align=center>&nbsp;".implode( "<br>", $aliased_to )."</td>\n";
            print "</tr>\n";
            dotline( 4 );
   }
    print "</table>\n";
    if ( !empty($row) ) {
      print "<br><br>\n";
      delete_selected_box();
    }
    print "</form>\n";
  }

  print_footer();

