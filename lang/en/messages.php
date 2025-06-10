<?php
// resources/lang/en/notices.php
return [
  'profile' => [
    'status' => [
      'active' => [
        'title' => 'Account Activated',
        'content' => 'Your account has been activated. You can now use all platform features.'
      ],
      'inactive' => [
        'title' => 'Account Deactivated',
        'content' => 'Your account has been deactivated. Please contact support for more information.'
      ]
    ],
    'role' => [
      'driver' => [
        'title' => 'Role Updated to Driver',
        'content' => 'Your account role has been updated to Driver. You can now access driver-specific features.'
      ],
      'seller' => [
        'title' => 'Role Updated to Seller',
        'content' => 'Your account role has been updated to Seller. You can now access seller-specific features.'
      ]
    ]
  ],
  'coupon' => [
    'title' => 'New Discount Coupon Available',
    'content' => 'Use coupon to get :discount% discount on your next purchase.'
  ],
  'order' => [
    'created' => [
      'title' => 'New Order #:order_id',
      'content' => 'New order received in :region_name'
    ],
    'pending' => [
      'title' => 'New Order Placed',
      'content' => 'Your order #:order_id has been placed successfully. Waiting for confirmation.'
    ],
    'accepted' => [
      'title' => 'Order Accepted',
      'content' => 'Your order #:order_id has been accepted and is being processed.'
    ],
    'canceled' => [
      'title' => 'Order Canceled',
      'content' => 'Your order #:order_id has been canceled.'
    ],
    'ongoing' => [
      'title' => 'Order Pickup',
      'content' => 'A driver has been assigned to your order #:order_id.'
    ],
    'arrived' => [
      'title' => 'Order Ready for Collection',
      'content' => 'Your order #:order_id has arrived. Please come outside to collect your order.'
    ],
    'delivered' => [
      'title' => 'Order Delivered',
      'content' => 'Your order #:order_id has been delivered successfully.'
    ]
  ],
  'product' => [
    'available' => [
      'title' => 'Product Now Available',
      'content' => ':product_name is now back in stock!'
    ],
    'unavailable' => [
      'title' => 'Product Out of Stock',
      'content' => ':product_name is currently out of stock.'
    ]
  ]
];
