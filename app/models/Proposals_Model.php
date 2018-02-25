<?php
class Proposals_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_proposals()
    {
        $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,proposals.id as id');
        $this->db->join('staff', 'proposals.assigned = staff.id', 'left');
        $this->db->where('(proposals.company_id = ' . $_SESSION['company_id'] . ') ');
        return $this->db->get('proposals')->result_array();
    }

    public function get_proposal($id)
    {
        return $this->db->get_where('proposals', array( 'id' => $id ))->row_array();
    }

    public function get_pro_rel_type($id)
    {
        return $this->db->get_where('proposals', array( 'id' => $id ))->row_array();
    }

    public function get_proposals($id, $rel_type)
    {
        if ($rel_type == 'customer') {
            $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,customers.companyname as customer,customers.companyemail as toemail,customers.namesurname as individual,customers.companyaddress as toaddress, proposals.id as id ');
            $this->db->join('customers', 'proposals.relation = customers.id', 'left');
            $this->db->join('staff', 'proposals.assigned = staff.id', 'left');
            return $this->db->get_where('proposals', array( 'proposals.id' => $id ))->row_array();
        } elseif ($rel_type == 'lead') {
            $this->db->select('*,staff.staffname as staffmembername,staff.staffavatar as staffmemberresim,leads.name as leadname,leads.address as toaddress,leads.email as toemail, proposals.id as id ');
            $this->db->join('leads', 'proposals.relation = leads.id', 'left');
            $this->db->join('staff', 'proposals.assigned = staff.id', 'left');
            return $this->db->get_where('proposals', array( 'proposals.id' => $id ))->row_array();
        }
    }

    public function get_proposalitems($id)
    {
        return $this->db->get_where('proposalitems', array( 'id' => $id ))->row_array();
    }
    // GET INVOICE DETAILS

    public function get_proposal_productsi_art($id)
    {
        $this->db->select_sum('in[total]');
        $this->db->from('proposalitems');
        $this->db->where('(proposalid = ' . $id . ') ');
        return $this->db->get();
    }

    // CHANCE INVOCE STATUS

    public function status_1($id)
    {
        $response = $this->db->where('id', $id)->update('proposals', array( 'statusid' => ('1') ));
        $response = $this->db->update('sales', array( 'proposalid' => $id, 'status' => '1' ));
    }

    public function status_2($id)
    {
        $response = $this->db->where('id', $id)->update('proposals', array( 'statusid' => ('2') ));
        $response = $this->db->update('sales', array( 'proposalid' => $id, 'status' => '2' ));
    }

    public function status_3($id)
    {
        $response = $this->db->where('id', $id)->update('proposals', array( 'statusid' => ('3') ));
        $response = $this->db->update('sales', array( 'proposalid' => $id, 'status' => '3' ));
    }
    // ADD INVOICE
    public function proposal_add($params)
    {
        $params['opentill']=($params['opentill'])?$params['opentill']:null;
        $params['datesend']=($params['datesend'])?$params['datesend']:null;

        
        $this->db->insert('proposals', $params);
        $proposal = $this->db->insert_id();
        // MULTIPLE INVOICE ITEMS POST
        $countitem = count($this->input->post('in[product_id]'));
        $countitem = $countitem - 1;
        for ($i = 0; $i < $countitem; $i++) {
            $iteminfo[] = array(
                'proposalid' => $proposal,
                'in[itemid]' => $this->input->post('in[itemid]')[ $i ],
                'in[name]' => $this->input->post('in[name]')[ $i ],
                'in[code]' => $this->input->post('in[code]')[ $i ],
                'in[product_id]' => ($this->input->post('in[product_id]')[ $i ])?$this->input->post('in[product_id]')[ $i ]:null,
                'in[description]' => $this->input->post('in[description]')[ $i ],
                'in[amount]' => $this->input->post('in[amount]')[ $i ],
                'in[unit]' => $this->input->post('in[unit]')[ $i ],
                'in[price]' => $this->input->post('in[pricepost]')[ $i ],
                'in[discount_rate]' => $this->input->post('in[discount_rate]')[ $i ],
                'in[price_discounted]' => $this->input->post('in[price_discounted]')[ $i ],
                'in[discount_type]' => $this->input->post('in[discount_type]')[ $i ],
                'in[discount_rate_status]' => $this->input->post('in[discount_rate_status]')[ $i ],
                'in[vat]' => str_replace(",", "", $this->input->post('in[vat]')[ $i ]),
                'in[total_vat]' => str_replace(",", "", $this->input->post('in[total_vat]')[ $i ]),
                'in[total]' => str_replace(",", "", $this->input->post('in[total]')[ $i ]),
            );
        }
        $this->db->insert_batch('proposalitems', $iteminfo);
        //LOG
        if ($this->input->post('relation') === 1) {

            //NOTIFICATION
            $staffname = $this->session->staffname;
            $staffavatar = $this->session->staffavatar;
            $this->db->insert('notifications', array(
                'date' => date('Y-m-d H:i:s'),
                'detail' => ('<div class="text"><span class="user-name">' . $staffname . '</span> ' . lang('isaddedanewproposal') . '</div>'),
                'customerid' => $this->input->post('relation'),
                'perres' => $staffavatar,
                'target' => '' . base_url('customer/proposal/' . $proposal . '') . '',
                'company_id' => $_SESSION['company_id'],
            ));
        }
        $staffname = $this->session->staffname;
        $loggedinuserid = $this->session->logged_in_staff_id;
        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('added') . ' <a href="/proposals/proposal/' . $proposal . '">' . lang('proposalprefix') . '-' . $proposal . '</a>.'),
            'staffid' => $loggedinuserid,
            'company_id' => $_SESSION['company_id']
        ));
        return $this->db->insert_id();
    }
    // UPDATE INVOCE
    public function update_proposals($id, $params) {

        $this->db->where( 'id', $id );
        $proposal = $id;

        $params['datesend'] = NULL;

        $response = $this->db->update('proposals', $params);

        /*var_dump( $params );
        echo '<br/>';
        echo '<br/>';
        var_dump( $params['datecreated'] );
        echo '<br/>';
        echo '<br/>';
        var_dump( $response );
        echo '<br/>';
        echo '<br/>';
        var_dump( $this->db->error() );
        die();*/

        $response = $this->db->delete('proposalitems', array( 'proposalid' => $id ));

        $input_values = $this->input->post('in');

        // MULTIPLE INVOICE ITEMS ADD
        $countitem = count( $input_values['itemid'] ); // in[newitem yapabilirsin]
        $countitem = $countitem - 1;
        
        $newitem = array();

        for ($i = 0; $i < $countitem; $i++) {
            $newitem[] = array(
                'proposalid' => $proposal,
                'in[name]' => $input_values['name'][ $i ],
                'in[code]' => $input_values['code'][ $i ],
                'in[product_id]' => $input_values['product_id'][ $i ],
                'in[description]' => $input_values['description'][ $i ],
                'in[amount]' => $input_values['amount'][ $i ],
                'in[unit]' => $input_values['unit'][ $i ],
                'in[price]' => $input_values['pricepost'][ $i ],
                'in[discount_rate]' => $input_values['discount_rate'][ $i ],
                'in[price_discounted]' => $input_values['price_discounted'][ $i ],
                'in[discount_type]' => $input_values['discount_type'][ $i ],
                'in[discount_rate_status]' => $input_values['discount_rate_status'][ $i ],
                'in[vat]' => str_replace(",", '', $input_values['vat'][ $i ]),
                'in[total_vat]' => str_replace(",", '', $input_values['total_vat'][ $i ]),
                'in[total]' => str_replace(",", '', $input_values['total'][ $i ]),
            );
        }
        
        if( ! empty( $newitem ) ){
            $this->db->insert_batch('proposalitems', $newitem);
        }

        //LOG
        $staffname = $this->session->staffname;
        $loggedinuserid = $this->session->logged_in_staff_id;
        
        $relation = 'customer' === $this->input->post('relation_type') ? $this->input->post('related_customer') : $this->input->post('related_lead');

        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('updated') . ' <a href="/proposals/proposal/' . $id . '">' . lang('proposalprefix') . '-' . $id . '</a>.'),
            'staffid' => $loggedinuserid,
            'customerid' => $relation,
            'company_id' => $_SESSION['company_id']
        ));

        //NOTIFICATION
        $staffname = $this->session->staffname;
        $staffavatar = $this->session->staffavatar;
        
        $this->db->insert('notifications', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<div class="text"><span class="user-name">' . $staffname . '</span> ' . lang('uptdatedproposal') . '</div>'),
            'customerid' => $relation,
            'perres' => $staffavatar,
            'target' => base_url( 'customer/proposal/' . $proposal )
        ));

        return $response ? "Proposal Updated." : "There was a problem during the update.";
    }
    //INVOICE DELETE
    public function delete_proposals($id)
    {
        $response = $this->db->delete('proposals', array( 'id' => $id ));
        $response = $this->db->delete('proposalitems', array( 'proposalid' => $id ));
        $staffname = $this->session->staffname;
        $loggedinuserid = $this->session->logged_in_staff_id;
        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('deleted') . ' ' . lang('proposalprefix') . '-' . $id . ''),
            'staffid' => $loggedinuserid,
            'company_id' => $_SESSION['company_id']
        ));
    }

    public function cancelled()
    {
        $response = $this->db->where('id', $_POST[ 'proposal' ])->update('proposals', array( 'statusid' => $_POST[ 'statusid' ] ));
    }

    public function markas()
    {
        $response = $this->db->where('id', $_POST[ 'proposal' ])->update('proposals', array( 'statusid' => $_POST[ 'statusid' ] ));
    }

    public function deleteproposalitem($id)
    {
        $response = $this->db->delete('proposalitems', array( 'id' => $id ));
    }
    
    public function get_proposal_year()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM proposals ORDER BY year DESC')->result_array();
    }
}
