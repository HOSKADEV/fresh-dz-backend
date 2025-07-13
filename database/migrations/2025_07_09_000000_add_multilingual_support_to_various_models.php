<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up()
  {
    // Add multilingual support to 'products' table
    Schema::table('products', function (Blueprint $table) {
      if (!Schema::hasColumn('products', 'name_ar')) {
        $table->string('name_ar')->nullable()->after('unit_name');
      }
      if (!Schema::hasColumn('products', 'name_fr')) {
        $table->string('name_fr')->nullable()->after('name_ar');
      }
      if (!Schema::hasColumn('products', 'name_en')) {
        $table->string('name_en')->nullable()->after('name_fr');
      }
    });

    // Add multilingual support to 'categories' table
    Schema::table('categories', function (Blueprint $table) {
      if (!Schema::hasColumn('categories', 'name_ar')) {
        $table->string('name_ar')->nullable()->after('name');
      }
      if (!Schema::hasColumn('categories', 'name_fr')) {
        $table->string('name_fr')->nullable()->after('name_ar');
      }
      if (!Schema::hasColumn('categories', 'name_en')) {
        $table->string('name_en')->nullable()->after('name_ar');
      }
    });

    // Add multilingual support to 'subcategories' table
    Schema::table('subcategories', function (Blueprint $table) {
      if (!Schema::hasColumn('subcategories', 'name_ar')) {
        $table->string('name_ar')->nullable()->after('name');
      }
      if (!Schema::hasColumn('subcategories', 'name_fr')) {
        $table->string('name_fr')->nullable()->after('name_ar');
      }
      if (!Schema::hasColumn('subcategories', 'name_en')) {
        $table->string('name_en')->nullable()->after('name_ar');
      }
    });

    // Add multilingual support to 'items' table if it doesn't already have it

    Schema::table('items', function (Blueprint $table): void {
      if (!Schema::hasColumn('items', 'name_ar')) {
        $table->string('name_ar')->nullable()->after('unit_name');
      }
      if (!Schema::hasColumn('items', 'name_en')) {
        $table->string('name_en')->nullable()->after('name_ar');
      }
      if (!Schema::hasColumn('items', 'name_fr')) {
        $table->string('name_fr')->nullable()->after('name_en');
      }
    });


    // Add multilingual support to 'ads' table if it doesn't already have it

    Schema::table('ads', function (Blueprint $table) {
      if (!Schema::hasColumn('ads', 'name_ar')) {
        $table->string('name_ar')->nullable()->after('name');
      }
      if (!Schema::hasColumn('ads', 'name_en')) {
        $table->string('name_en')->nullable()->after('name_ar');
      }
      if (!Schema::hasColumn('ads', 'name_fr')) {
        $table->string('name_fr')->nullable()->after('name_en');
      }
    });


    // Add French support to 'groups' table if it doesn't already have it
    Schema::table('groups', function (Blueprint $table): void {
      if (!Schema::hasColumn('groups', 'name_ar')) {
        $table->string('name_ar')->nullable()->after('name');
      }
      if (!Schema::hasColumn('groups', 'name_fr')) {
        $table->string('name_fr')->nullable()->after('name_en');
      }
    });


    // Add French support to 'families' table if it doesn't already have it
    Schema::table('families', function (Blueprint $table): void {
      if (!Schema::hasColumn('families', 'name_ar')) {
        $table->string('name_ar')->nullable()->after('name');
      }
      if (!Schema::hasColumn('families', 'name_fr')) {
        $table->string('name_fr')->nullable()->after('name_en');
      }
    });

    // Add French support to 'offers' table if it doesn't already have it
    Schema::table('offers', function (Blueprint $table): void {
      if (!Schema::hasColumn('offers', 'name_ar')) {
        $table->string('name_ar')->nullable()->after('name');
      }
      if (!Schema::hasColumn('offers', 'name_fr')) {
        $table->string('name_fr')->nullable()->after('name_en');
      }
    });

    // Add French support to 'units' table if it doesn't already have it
    Schema::table('units', function (Blueprint $table) {
      if (!Schema::hasColumn('units', 'name_fr')) {
        $table->string('name_fr')->nullable()->after('name_en');
      }
    });


    // Add French support to 'documentations' table if it doesn't already have it
    Schema::table('documentations', function (Blueprint $table) {
      if (!Schema::hasColumn('documentations', 'content_fr')) {
        $table->string('content_fr')->nullable()->after('content_en');
      }
    });


    // Add French support to 'notices' table if it doesn't already have it
    Schema::table('notices', function (Blueprint $table) {
      if (!Schema::hasColumn('notices', 'title_fr')) {
        $table->string('title_fr')->nullable()->after('title_en');
      }
      if (!Schema::hasColumn('notices', 'content_fr')) {
        $table->text('content_fr')->nullable()->after('content_en');
      }
    });
  }

  public function down()
  {
    // Remove multilingual support from 'products' table
    Schema::table('products', function (Blueprint $table) {
      $table->dropColumn(['name_en', 'name_fr', 'name_ar']);
    });
    // Remove multilingual support from 'categories' table
    Schema::table('categories', function (Blueprint $table) {
      $table->dropColumn(['name_ar', 'name_en', 'name_fr']);
    });

    // Remove multilingual support from 'subcategories' table
    Schema::table('subcategories', function (Blueprint $table) {
      $table->dropColumn(['name_ar', 'name_en', 'name_fr']);
    });

    // Remove multilingual support from 'items' table if columns exist
    if (Schema::hasColumn('items', 'name_ar')) {
      Schema::table('items', function (Blueprint $table) {
        $table->dropColumn(['name_ar', 'name_en', 'name_fr']);
      });
    }

    // Remove multilingual support from 'ads' table if columns exist
    if (Schema::hasColumn('ads', 'name_ar')) {
      Schema::table('ads', function (Blueprint $table) {
        $table->dropColumn(['name_ar', 'name_en', 'name_fr']);
      });
    }

    // Remove French support from 'groups' table if column exists
    if (Schema::hasColumn('groups', 'name_fr')) {
      Schema::table('groups', function (Blueprint $table) {
        $table->dropColumn('name_fr');
      });
    }

    // Remove French support from 'families' table if column exists
    if (Schema::hasColumn('families', 'name_fr')) {
      Schema::table('families', function (Blueprint $table) {
        $table->dropColumn('name_fr');
      });
    }

    // Remove French support from 'offers' table if column exists
    if (Schema::hasColumn('offers', 'name_fr')) {
      Schema::table('offers', function (Blueprint $table) {
        $table->dropColumn('name_fr');
      });
    }

    // Remove French support from 'units' table if column exists
    if (Schema::hasColumn('units', 'name_fr')) {
      Schema::table('units', function (Blueprint $table) {
        $table->dropColumn('name_fr');
      });
    }

    // Remove French support from 'documentations' table if column exists
    if (Schema::hasColumn('documentations', 'content_fr')) {
      Schema::table('documentations', function (Blueprint $table) {
        $table->dropColumn('content_fr');
      });
    }

    // Remove French support from 'notices' table if column exists
    if (Schema::hasColumn('notices', 'title_fr')) {
      Schema::table('notices', function (Blueprint $table) {
        $table->dropColumn('title_fr');
      });
    }
    if (Schema::hasColumn('notices', 'content_fr')) {
      Schema::table('notices', function (Blueprint $table) {
        $table->dropColumn('content_fr');
      });
    }
  }
};
