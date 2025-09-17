<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute kabul edilmelidir.',
    'accepted_if' => ':other :value olduğunda :attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL olmalıdır.',
    'after' => ':attribute :date tarihinden sonra bir tarih olmalıdır.',
    'after_or_equal' => ':attribute :date tarihinden sonra veya eşit bir tarih olmalıdır.',
    'alpha' => ':attribute sadece harflerden oluşmalıdır.',
    'alpha_dash' => ':attribute sadece harfler, rakamlar, tire ve alt çizgilerden oluşmalıdır.',
    'alpha_num' => ':attribute sadece harfler ve rakamlar içermelidir.',
    'array' => ':attribute bir dizi olmalıdır.',
    'ascii' => ':attribute sadece tek baytlık alfanumerik karakterler ve semboller içermelidir.',
    'before' => ':attribute :date tarihinden önce bir tarih olmalıdır.',
    'before_or_equal' => ':attribute :date tarihinden önce veya eşit bir tarih olmalıdır.',
    'between' => [
        'array' => ':attribute :min - :max arasında öğe içermelidir.',
        'file' => ':attribute :min - :max kilobayt arasında olmalıdır.',
        'numeric' => ':attribute :min - :max arasında olmalıdır.',
        'string' => ':attribute :min - :max karakter arasında olmalıdır.',
    ],
    'boolean' => ':attribute alanı doğru veya yanlış olmalıdır.',
    'can' => ':attribute alanı geçerli bir yetki içermelidir.',
    'confirmed' => ':attribute doğrulaması eşleşmiyor.',
    'current_password' => 'Şifre yanlış.',
    'date' => ':attribute geçerli bir tarih olmalıdır.',
    'date_equals' => ':attribute :date ile aynı tarih olmalıdır.',
    'date_format' => ':attribute :format formatına uygun olmalıdır.',
    'decimal' => ':attribute :decimal ondalık basamağa sahip olmalıdır.',
    'declined' => ':attribute reddedilmelidir.',
    'declined_if' => ':other :value olduğunda :attribute reddedilmelidir.',
    'different' => ':attribute ile :other farklı olmalıdır.',
    'digits' => ':attribute :digits basamak olmalıdır.',
    'digits_between' => ':attribute :min - :max basamak arasında olmalıdır.',
    'dimensions' => ':attribute geçersiz resim boyutlarına sahip.',
    'distinct' => ':attribute alanı yinelenen bir değere sahip.',
    'doesnt_end_with' => ':attribute şu değerlerden biriyle bitmemelidir: :values.',
    'doesnt_start_with' => ':attribute şu değerlerden biriyle başlamamalıdır: :values.',
    'email' => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => ':attribute şu değerlerden biriyle bitmelidir: :values.',
    'enum' => 'Seçilen :attribute geçersiz.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'extensions' => ':attribute alanı şu uzantılardan birine sahip olmalıdır: :values.',
    'file' => ':attribute bir dosya olmalıdır.',
    'filled' => ':attribute alanı bir değere sahip olmalıdır.',
    'gt' => [
        'array' => ':attribute :value öğeden fazla içermelidir.',
        'file' => ':attribute :value kilobayttan büyük olmalıdır.',
        'numeric' => ':attribute :value sayısından büyük olmalıdır.',
        'string' => ':attribute :value karakterinden uzun olmalıdır.',
    ],
    'gte' => [
        'array' => ':attribute :value veya daha fazla öğe içermelidir.',
        'file' => ':attribute :value kilobayt veya daha büyük olmalıdır.',
        'numeric' => ':attribute :value sayısından büyük veya eşit olmalıdır.',
        'string' => ':attribute :value karakter veya daha uzun olmalıdır.',
    ],
    'hex_color' => ':attribute alanı geçerli bir onaltılık renk olmalıdır.',
    'image' => ':attribute bir resim olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'in_array' => ':attribute alanı :other içinde mevcut değil.',
    'integer' => ':attribute bir tamsayı olmalıdır.',
    'ip' => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute geçerli bir JSON string olmalıdır.',
    'lowercase' => ':attribute küçük harf olmalıdır.',
    'lt' => [
        'array' => ':attribute :value öğeden az içermelidir.',
        'file' => ':attribute :value kilobayttan küçük olmalıdır.',
        'numeric' => ':attribute :value sayısından küçük olmalıdır.',
        'string' => ':attribute :value karakterinden kısa olmalıdır.',
    ],
    'lte' => [
        'array' => ':attribute :value öğeden fazla içermemelidir.',
        'file' => ':attribute :value kilobayt veya daha küçük olmalıdır.',
        'numeric' => ':attribute :value sayısından küçük veya eşit olmalıdır.',
        'string' => ':attribute :value karakter veya daha kısa olmalıdır.',
    ],
    'mac_address' => ':attribute geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => ':attribute :max öğeden fazla içermemelidir.',
        'file' => ':attribute :max kilobayttan büyük olmamalıdır.',
        'numeric' => ':attribute :max sayısından büyük olmamalıdır.',
        'string' => ':attribute :max karakterden uzun olmamalıdır.',
    ],
    'max_digits' => ':attribute :max basamaktan fazla olmamalıdır.',
    'mimes' => ':attribute :values türünde bir dosya olmalıdır.',
    'mimetypes' => ':attribute :values türünde bir dosya olmalıdır.',
    'min' => [
        'array' => ':attribute en az :min öğe içermelidir.',
        'file' => ':attribute en az :min kilobayt olmalıdır.',
        'numeric' => ':attribute en az :min olmalıdır.',
        'string' => ':attribute en az :min karakter olmalıdır.',
    ],
    'min_digits' => ':attribute en az :min basamak olmalıdır.',
    'missing' => ':attribute alanı mevcut olmamalıdır.',
    'missing_if' => ':other :value olduğunda :attribute alanı mevcut olmamalıdır.',
    'missing_unless' => ':other :value olmadığı sürece :attribute alanı mevcut olmamalıdır.',
    'missing_with' => ':values mevcut olduğunda :attribute alanı mevcut olmamalıdır.',
    'missing_with_all' => ':values mevcut olduğunda :attribute alanı mevcut olmamalıdır.',
    'multiple_of' => ':attribute :value sayısının katı olmalıdır.',
    'not_in' => 'Seçilen :attribute geçersiz.',
    'not_regex' => ':attribute formatı geçersiz.',
    'numeric' => ':attribute bir sayı olmalıdır.',
    'password' => 'Şifre yanlış.',
    'present' => ':attribute alanı mevcut olmalıdır.',
    'present_if' => ':other :value olduğunda :attribute alanı mevcut olmalıdır.',
    'present_unless' => ':other :value olmadığı sürece :attribute alanı mevcut olmalıdır.',
    'present_with' => ':values mevcut olduğunda :attribute alanı mevcut olmalıdır.',
    'present_with_all' => ':values mevcut olduğunda :attribute alanı mevcut olmalıdır.',
    'prohibited' => ':attribute alanı yasaklanmıştır.',
    'prohibited_if' => ':other :value olduğunda :attribute alanı yasaklanmıştır.',
    'prohibited_unless' => ':other :values içinde olmadığı sürece :attribute alanı yasaklanmıştır.',
    'prohibits' => ':attribute alanı :other mevcut olmasını yasaklar.',
    'regex' => ':attribute formatı geçersiz.',
    'required' => ':attribute alanı zorunludur.',
    'required_array_keys' => ':attribute alanı şu girişleri içermelidir: :values.',
    'required_if' => ':other :value olduğunda :attribute alanı zorunludur.',
    'required_if_accepted' => ':other kabul edildiğinde :attribute alanı zorunludur.',
    'required_unless' => ':other :values içinde olmadığı sürece :attribute alanı zorunludur.',
    'required_with' => ':values mevcut olduğunda :attribute alanı zorunludur.',
    'required_with_all' => ':values mevcut olduğunda :attribute alanı zorunludur.',
    'required_without' => ':values mevcut olmadığında :attribute alanı zorunludur.',
    'required_without_all' => ':values hiçbiri mevcut olmadığında :attribute alanı zorunludur.',
    'same' => ':attribute ile :other eşleşmelidir.',
    'size' => [
        'array' => ':attribute :size öğe içermelidir.',
        'file' => ':attribute :size kilobayt olmalıdır.',
        'numeric' => ':attribute :size olmalıdır.',
        'string' => ':attribute :size karakter olmalıdır.',
    ],
    'starts_with' => ':attribute şu değerlerden biriyle başlamalıdır: :values.',
    'string' => ':attribute bir string olmalıdır.',
    'timezone' => ':attribute geçerli bir saat dilimi olmalıdır.',
    'unique' => ':attribute zaten alınmış.',
    'uploaded' => ':attribute yüklenemedi.',
    'uppercase' => ':attribute büyük harf olmalıdır.',
    'url' => ':attribute geçerli bir URL olmalıdır.',
    'ulid' => ':attribute geçerli bir ULID olmalıdır.',
    'uuid' => ':attribute geçerli bir UUID olmalıdır.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'Ad Soyad',
        'username' => 'Kullanıcı Adı',
        'email' => 'E-posta',
        'password' => 'Şifre',
        'password_confirmation' => 'Şifre Tekrarı',
        'role' => 'Rol',
        'remember' => 'Beni Hatırla',
    ],

];
