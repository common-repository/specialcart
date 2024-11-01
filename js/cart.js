

window.addEventListener('DOMContentLoaded',()=>{
    initSpecialCart()
})







function createCartProduct(key,q,title,price,img){
    let box = document.createElement('div')
    box.className = 'single-product-box'

    let boxHalf1 = document.createElement('div')
    boxHalf1.className = 'single-product-half1'

    boxHalf1.innerHTML = img

    let boxHalf2 = document.createElement('div')
    boxHalf2.className = 'single-product-half2'

    let boxTitle = document.createElement('b')
    boxTitle.textContent = title

    boxHalf2.appendChild(boxTitle)

    let boxPrice = document.createElement('p')
    boxPrice.className = 'single-p-price'
    boxPrice.textContent = price
   

    let boxInput = document.createElement('input')
    boxInput.type = 'text'
    boxInput.value = q
    boxInput.className = 'input-cart-quy qty text form-control'
    boxInput.name = 'cart[1e334311e1ef4cf849abff19e4237358][qty]'
    boxInput.setAttribute('data-value',key)

    let boxMinus = document.createElement('div')
    boxMinus.className = 'minus buttonquy'
    boxMinus.textContent = '-'

    let boxPlus = document.createElement('div')
    boxPlus.className = 'plus buttonquy'
    boxPlus.textContent = '+'

    

    let boxQty = document.createElement('div')
    boxQty.className = 'quantity-sp-o form-group'

    let boxQtyF = document.createElement('p')

    boxQty.appendChild(boxQtyF)


    boxHalf2.appendChild(boxPrice)

    boxHalf2.appendChild(boxQty)
    boxQty.appendChild(boxMinus)
    boxQty.appendChild(boxInput)
    boxQty.appendChild(boxPlus)



    box.appendChild(boxHalf1)
    box.appendChild(boxHalf2)

    document.querySelector('#specialcart-sidebar-products-container').appendChild(box)

    document.querySelectorAll('.quantity-sp-o').forEach(s=>{
        s.children[3].addEventListener('click',()=>{
            s.children[2].value = parseInt(s.children[2].value)  + 1
            s.children[2].dispatchEvent(new Event('change', { 'bubbles': true }))
        })

        s.children[1].addEventListener('click',()=>{
            s.children[2].value = parseInt(s.children[2].value)  - 1
            s.children[2].dispatchEvent(new Event('change', { 'bubbles': true }))
        })

    })

    boxPrice.textContent = price +' ' + document.querySelector('.woocommerce-Price-currencySymbol').textContent

}


function initSpecialCart()
{
    let cartButton = document.querySelector('#cartB')
    let cartBG = document.querySelector('#cartsidebardiv')
    let cartDiv = document.querySelector('#cartsidebar')
    let cartCloseButton = document.querySelector('.cart-sidebar-close')

    cartButton.addEventListener('click',()=>{

        cartBG.style.display ='block'
        setTimeout(() => {
            cartDiv.style.right = '0%'
        }, 1);


    })

    cartCloseButton.addEventListener('click',()=>{

        cartDiv.style.right = '-25%'
        setTimeout(() => {
            cartBG.style.display ='none'

        }, 500);

    })



    document.querySelectorAll('.quantity-sp-o').forEach(s=>{
        s.children[3].addEventListener('click',()=>{
            s.children[2].value = parseInt(s.children[2].value)  + 1
            s.children[2].dispatchEvent(new Event('change', { 'bubbles': true }))
        })

        s.children[1].addEventListener('click',()=>{
            s.children[2].value = parseInt(s.children[2].value)  - 1
            s.children[2].dispatchEvent(new Event('change', { 'bubbles': true }))
        })

    })
}


jQuery(document).on('click', '.quantity-sp-o', function (e)
    {
            e.preventDefault();
            var qty = jQuery(this)[0].children[2].value;
            var cart_item_key =jQuery(this)[0].children[2].getAttribute('data-value')

          
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: window.location.origin + '/wp-admin/admin-ajax.php',
                data: {action : 'update_item_from_cart', 'cart_item_key' : cart_item_key, 'qty' : qty,  },
                success: function (data) {
                    console.log('reduce product quantity sucessful')



                    let data_cart_total = {
                        action: 'get_cart_total'
                    };

                    jQuery.post( cartScript.ajaxurl, data_cart_total, function () {
                    } ).done( function ( response ) {
                        console.log(response)
                        document.querySelector('#subtotal-special-cart').innerHTML = response.data
                    } ).fail( function ( response ) {
                        
                    } );

                    let data_cart = {
                        action: 'get_cart_items'
                    };
        
                    jQuery.post( cartScript.ajaxurl, data_cart, function () {
                    } ).done( function ( response ) {
                        if(parseInt(qty) === 0)
                        {
        
                            document.querySelector('#specialcart-sidebar-products-container').textContent = ''
        
                            response.data.forEach(r=>{
                                createCartProduct(r[0],r[4],r[1],r[3],r[2])
                            })
                            
                        }
                        // console.log(response)
                    } ).fail( function ( response ) {
                        
                    } );
        
                  

                if (data) {
                  
                     
                }else{
                    // alert('Updated Successfully');
                }
            }

            });



          
        
          



    });