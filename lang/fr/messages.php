<?php
// resources/lang/fr/notices.php
return [
  'profile' => [
    'status' => [
      'active' => [
        'title' => 'Compte Activé',
        'content' => 'Votre compte a été activé. Vous pouvez maintenant utiliser toutes les fonctionnalités de la plateforme.'
      ],
      'inactive' => [
        'title' => 'Compte Désactivé',
        'content' => 'Votre compte a été désactivé. Veuillez contacter le support pour plus d\'informations.'
      ]
    ],
    'role' => [
      'driver' => [
        'title' => 'Rôle Mis à Jour vers Chauffeur',
        'content' => 'Le rôle de votre compte a été mis à jour vers Chauffeur. Vous pouvez maintenant accéder aux fonctionnalités spécifiques aux chauffeurs.'
      ],
      'seller' => [
        'title' => 'Rôle Mis à Jour vers Vendeur',
        'content' => 'Le rôle de votre compte a été mis à jour vers Vendeur. Vous pouvez maintenant accéder aux fonctionnalités spécifiques aux vendeurs.'
      ]
    ]
  ],
  'coupon' => [
    'title' => 'Nouveau Coupon de Réduction Disponible',
    'content' => 'Utilisez le coupon pour obtenir :discount% de réduction sur votre prochain achat.'
  ],
  'order' => [
    'created' => [
      'title' => 'Nouvelle Commande #:order_id',
      'content' => 'Nouvelle commande reçue dans :region_name'
    ],
    'pending' => [
      'title' => 'Nouvelle Commande Passée',
      'content' => 'Votre commande #:order_id a été passée avec succès. En attente de confirmation.'
    ],
    'accepted' => [
      'title' => 'Commande Acceptée',
      'content' => 'Votre commande #:order_id a été acceptée et est en cours de traitement.'
    ],
    'canceled' => [
      'title' => 'Commande Annulée',
      'content' => 'Votre commande #:order_id a été annulée.'
    ],
    'ongoing' => [
      'title' => 'Prise en Charge de la Commande',
      'content' => 'Un chauffeur a été assigné à votre commande #:order_id.'
    ],
    'arrived' => [
      'title' => 'Commande Prête pour la Collecte',
      'content' => 'Votre commande #:order_id est arrivée. Veuillez sortir pour récupérer votre commande.'
    ],
    'delivered' => [
      'title' => 'Commande Livrée',
      'content' => 'Votre commande #:order_id a été livrée avec succès.'
    ]
  ],
  'product' => [
    'available' => [
      'title' => 'Produit Maintenant Disponible',
      'content' => ':product_name est de nouveau en stock !'
    ],
    'unavailable' => [
      'title' => 'Produit En Rupture de Stock',
      'content' => ':product_name est actuellement en rupture de stock.'
    ]
    ],

    'discount' => [
      'default' => [
        'title' => 'Nouvelle Réduction sur Produit',
        'content' => 'Obtenez :discount% de réduction sur :product_name !'
      ]
    ]
];
