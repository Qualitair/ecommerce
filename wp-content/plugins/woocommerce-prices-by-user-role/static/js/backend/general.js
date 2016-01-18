jQuery(document).ready(function() 
{

    jQuery('input[data-event=visible]').change(function(){ 
       
        var className = jQuery(this).attr("name") + '-' + jQuery(this).data('event');

        if(jQuery(this).attr("checked")){ 
            
            jQuery('.'+className).fadeIn();         
        } else { 
            jQuery('.'+className).fadeOut(100);
        } 
    });

    jQuery('select[data-event=visible]').change(function(){ 
        var className = jQuery(this).attr("name") + '-' + jQuery(this).data('event');

        if(jQuery(this).val() == 'disable'){ 
            jQuery('.'+className).fadeOut(100);      
        } else { 
            jQuery('.'+className).fadeIn();
        }
    });
    
    jQuery('.festi-user-role-prices-delete-role').click(function() 
    {
        if (!confirm('Are you sure to delete')) {
             return false;
        }
    });
    
    jQuery('#festi-user-role-prices-discount-roles input[type=number]').live('keypress', function(e)
    {
        if( e.which!=8 && e.which!=0 && e.which!=46 && (e.which<48 || e.which>57))
        {
            return false;
        } 
    });
  
}); 