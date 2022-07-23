<?php
add_action( 'porto_after_wrapper','nd_state_selection_popup' );

function nd_state_selection_popup(){
    ?>
    <!-- Modal -->
    <div class="modal fade" id="nd_state_selection" tabindex="-1" role="dialog" aria-labelledby="nd_state_selection" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

<!--                <div class="modal-header">-->
<!--                    <h5 class="modal-title" id="nd_state_selectionTitle">Where would you like to build?</h5>-->
<!--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
<!--                        <span aria-hidden="true">&times;</span>-->
<!--                    </button>-->
<!--                </div>-->
                <div class="modal-body">
                    <div class="nd-main-content text-center p-3 p-lg-4 px-lg-5"">
                        <h2 class="modal-title mb-4" id="nd_state_selectionTitle">Where would you like to build?</h2>
                        <p>This information helps us show you the home designs, display centres, and packages that are most relevant to you.</p>


                <?php
                $current_states = csp_get_current_states();
//                        echo '<pre>';
//                        var_dump($current_state);
                foreach($current_states as $state){
                ?>
                    <div class="vc_btn3-container vc_btn3-inline">
                        <button class="vc_btn3 vc_btn3-shape-rounded btn btn-modern btn-md btn-primary"><?php echo $state['region_code']; ?></button>
                    </div>
                      <?php  }?>
                    </div>
                </div>


<!--                <div class="modal-footer">-->
<!--                    <button type="button" class="close" data-dismiss="modal">Close</button>-->
<!--                    <button type="button" class="btn btn-primary">Save changes</button>-->
<!--                </div>-->
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($){

            function hc_update_state_cookie(){
                alert('Cookie set');
            }

            $(document).on('click', '.close', function() {
                // $('.modal-backdrop').remove();
                // hc_update_state_cookie();
                $('#nd_state_selection').modal('hide');
                // setTimeout(function(){ $('#nd_state_selection.modal').hide(); }, 500);
            });
            $('#nd_state_selection').modal('show');

            $('#nd_state_selection').on('hidden.bs.modal', function (e) {
                hc_update_state_cookie();
            })

            // var state_selected = jQuery.cookie('nd_state');
            // if( state_selected != 'true' ) {
            //     $('#nd_state_selection').modal('show');
            // }

        });
    </script>



<?php
}
