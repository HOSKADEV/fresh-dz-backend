<?php
// resources/lang/ar/notices.php
return [
  'profile' => [
    'status' => [
      'active' => [
        'title' => 'تم تفعيل الحساب',
        'content' => 'تم تفعيل حسابك. يمكنك الآن استخدام جميع ميزات المنصة.'
      ],
      'inactive' => [
        'title' => 'تم تعطيل الحساب',
        'content' => 'تم تعطيل حسابك. يرجى الاتصال بالدعم لمزيد من المعلومات.'
      ]
    ],
    'role' => [
      'driver' => [
        'title' => 'تم تحديث الدور إلى سائق',
        'content' => 'تم تحديث دور حسابك إلى سائق. يمكنك الآن الوصول إلى الميزات الخاصة بالسائقين.'
      ],
      'seller' => [
        'title' => 'تم تحديث الدور إلى بائع',
        'content' => 'تم تحديث دور حسابك إلى بائع. يمكنك الآن الوصول إلى الميزات الخاصة بالبائعين.'
      ]
    ]
  ],
  'coupon' => [
    'title' => 'كوبون خصم جديد متاح',
    'content' => 'استخدم الكوبون للحصول على خصم :discount% على مشترياتك التالية.'
  ],
  'order' => [
    'created' => [
      'title' => 'طلب جديد #:order_id',
      'content' => 'تم استلام طلب جديد في :region_name'
    ],
    'pending' => [
      'title' => 'تم إنشاء طلب جديد',
      'content' => 'تم تقديم طلبك رقم #:order_id بنجاح. في انتظار التأكيد.'
    ],
    'accepted' => [
      'title' => 'تم قبول الطلب',
      'content' => 'تم قبول طلبك رقم #:order_id وجاري معالجته.'
    ],
    'canceled' => [
      'title' => 'تم إلغاء الطلب',
      'content' => 'تم إلغاء طلبك رقم #:order_id.'
    ],
    'ongoing' => [
      'title' => 'جاري استلام الطلب',
      'content' => 'تم تعيين سائق لطلبك رقم #:order_id.'
    ],
    'arrived' => [
      'title' => 'الطلب جاهز للاستلام',
      'content' => 'وصل طلبك رقم #:order_id. يرجى الخروج لاستلام طلبك.'
    ],
    'delivered' => [
      'title' => 'تم التوصيل',
      'content' => 'تم توصيل طلبك رقم #:order_id بنجاح.'
    ]
  ],

  'product' => [
    'available' => [
      'title' => 'المنتج متوفر الآن',
      'content' => ':product_name متوفر الآن في المخزون!'
    ],
    'unavailable' => [
      'title' => 'المنتج غير متوفر',
      'content' => ':product_name غير متوفر حالياً في المخزون.'
    ]
    ],

    'discount' => [
      'default' => [
        'title' => 'خصم جديد على المنتج',
        'content' => 'احصل على خصم :discount% على :product_name!'
      ]
    ]

];
