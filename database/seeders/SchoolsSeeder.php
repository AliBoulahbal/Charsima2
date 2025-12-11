<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Début de l\'importation des écoles...');
        
        // Vider la table si nécessaire (décommenter pour vider)
        // School::truncate();
        
        $totalSchools = 0;
        
        // Wilaya 1: الجلفة (Djelfa)
        $this->importDjelfaSchools();
        $totalSchools += School::where('wilaya', 'الجلفة')->count();
        
        // Wilaya 2: اولاد جلال (Ouled Djellal)
        $this->importOuledDjellalSchools();
        $totalSchools += School::where('wilaya', 'اولاد جلال')->count();
        
        // Wilaya 3: بسكرة (Biskra)
        $this->importBiskraSchools();
        $totalSchools += School::where('wilaya', 'بسكرة')->count();
        
        // Wilaya 4: سكيكدة (Skikda)
        $this->importSkikdaSchools();
        $totalSchools += School::where('wilaya', 'سكيكدة')->count();
        
        // Wilaya 5: عنابة (Annaba)
        $this->importAnnabaSchools();
        $totalSchools += School::where('wilaya', 'عنابة')->count();
        
        // Wilaya 6: قسنطينة (Constantine)
        $this->importConstantineSchools();
        $totalSchools += School::where('wilaya', 'قسنطينة')->count();
        
        // Wilaya 7: لمغير (El M'Ghair)
        $this->importElMghairSchools();
        $totalSchools += School::where('wilaya', 'لمغير')->count();
        
        // Wilaya 8: مسيلة (M'Sila)
        $this->importMSilaSchools();
        $totalSchools += School::where('wilaya', 'مسيلة')->count();
        
        // Wilaya 9: ميلة (Mila)
        $this->importMilaSchools();
        $totalSchools += School::where('wilaya', 'ميلة')->count();
        
        $this->command->info('Importation terminée !');
        $this->command->info("Total des écoles importées : $totalSchools");
    }
    
    /**
     * Wilaya: الجلفة (Djelfa)
     */
    private function importDjelfaSchools(): void
    {
        $this->command->info('Importation des écoles de الجلفة...');
        
        $schools = [
            // Commune: البيرين
            [
                'name' => 'البشير الابراهيمي',
                'district' => 'البيرين',
                'phone' => '0779370822',
                'manager_name' => 'امسعودان مراد',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بوزيان بن يحي',
                'district' => 'البيرين',
                'phone' => '0663419387',
                'manager_name' => 'شحيم الببشير',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بوعقلين الحاج',
                'district' => 'البيرين',
                'phone' => '0541152325',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'حميداني محمد',
                'district' => 'البيرين',
                'phone' => '0776319012',
                'manager_name' => 'حمزة عبد الكريم',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'سائحي مختار',
                'district' => 'البيرين',
                'phone' => '0771703300',
                'manager_name' => 'شنوف رشيدة',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'قاسمي بن علية',
                'district' => 'البيرين',
                'phone' => '0558621055',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'محمد بن سليم',
                'district' => 'البيرين',
                'phone' => '0793626784',
                'manager_name' => 'عبد الوهاب عبد المجيد',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'محمد عبد الوهاب',
                'district' => 'البيرين',
                'phone' => '0795902754',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'المختار بخوش',
                'district' => 'البيرين',
                'phone' => '0774186501',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            
            // Commune: الجلفة وسط
            [
                'name' => '20اوت1956',
                'district' => 'الجلفة وسط',
                'phone' => '0662063837',
                'manager_name' => 'ب, بن مسعود',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'الابتدائية المركزية',
                'district' => 'الجلفة وسط',
                'phone' => '0668079519',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'الامام بن ربيح',
                'district' => 'الجلفة وسط',
                'phone' => '0660429279',
                'manager_name' => '/',
                'student_count' => 530,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'السعدي بلقاسم',
                'district' => 'الجلفة وسط',
                'phone' => '0665386565',
                'manager_name' => 'عمر الود',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'الشيخ احمد بن علي',
                'district' => 'الجلفة وسط',
                'phone' => '0667831859',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'الشيهب بلخير',
                'district' => 'الجلفة وسط',
                'phone' => '0656003131',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'القطب الحضري بربيع',
                'district' => 'الجلفة وسط',
                'phone' => '0655223020',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'العقون مصطفى',
                'district' => 'الجلفة وسط',
                'phone' => '0671302665',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'القزي عبد الحميد',
                'district' => 'الجلفة وسط',
                'phone' => '0696970412',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'الكر الطاهر',
                'district' => 'الجلفة وسط',
                'phone' => '0660949588',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'الهاني محمد بن الهادي',
                'district' => 'الجلفة وسط',
                'phone' => '0676056546',
                'manager_name' => 'زروث رقية',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بالي العيد',
                'district' => 'الجلفة وسط',
                'phone' => '0661760122',
                'manager_name' => 'عبد اللطيف نور الهدى',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بطاش نعاس',
                'district' => 'الجلفة وسط',
                'phone' => '0666925817',
                'manager_name' => 'عبد الحميد شعش',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بلعربي عبد الباقي',
                'district' => 'الجلفة وسط',
                'phone' => '0657457628',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بلعطرة قويدر',
                'district' => 'الجلفة وسط',
                'phone' => '0668148115',
                'manager_name' => 'الود مصطفى',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بلعطرة مختار',
                'district' => 'الجلفة وسط',
                'phone' => '027934378',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بن حليمة محمد',
                'district' => 'الجلفة وسط',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بن عالية علي',
                'district' => 'الجلفة وسط',
                'phone' => '0660878797',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بن علية علي شعوة',
                'district' => 'الجلفة وسط',
                'phone' => '0660878797',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بن عمران تامر',
                'district' => 'الجلفة وسط',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بورزق بوبكر',
                'district' => 'الجلفة وسط',
                'phone' => '0659527456',
                'manager_name' => 'شارف بن سعد',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بورقدة احمد',
                'district' => 'الجلفة وسط',
                'phone' => '0675962343',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بوزيدي زكراوي',
                'district' => 'الجلفة وسط',
                'phone' => '0696962265',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'تفريج محمد',
                'district' => 'الجلفة وسط',
                'phone' => '0660937364',
                'manager_name' => 'بكاي أ',
                'student_count' => 318,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'جربيع سعد بن الميلود',
                'district' => 'الجلفة وسط',
                'phone' => '0671401798',
                'manager_name' => '/',
                'student_count' => 300,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'جعيد احمد',
                'district' => 'الجلفة وسط',
                'phone' => '0696022213',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'جعيد عمر',
                'district' => 'الجلفة وسط',
                'phone' => '0699441321',
                'manager_name' => 'صادقي عمر',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'حرفوش عبد القادر',
                'district' => 'الجلفة وسط',
                'phone' => '0654929924',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'حرفوش عيسى',
                'district' => 'الجلفة وسط',
                'phone' => '0656301246',
                'manager_name' => 'عدلي عبد العزيز',
                'student_count' => 700,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'جواف علي',
                'district' => 'الجلفة وسط',
                'phone' => '0676207135',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'حفاف يحيى',
                'district' => 'الجلفة وسط',
                'phone' => '0664007871',
                'manager_name' => '/',
                'student_count' => 350,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'حلفاوي مصطفى',
                'district' => 'الجلفة وسط',
                'phone' => '0658655148',
                'manager_name' => '/',
                'student_count' => 350,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'حنيشي محمد الجنوبية',
                'district' => 'الجلفة وسط',
                'phone' => '0674056962',
                'manager_name' => 'بوساسي وردة',
                'student_count' => 320,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'حنيشي محمد الشمالية',
                'district' => 'الجلفة وسط',
                'phone' => '0672885888',
                'manager_name' => 'سليمة',
                'student_count' => 509,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'خدومة سعد',
                'district' => 'الجلفة وسط',
                'phone' => '0699717178',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'دروازي الشامخ 01',
                'district' => 'الجلفة وسط',
                'phone' => '0697392052',
                'manager_name' => 'قويلي عبد القادر',
                'student_count' => 50,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'دريسي عيسى',
                'district' => 'الجلفة وسط',
                'phone' => '0666507356',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'دلاعة عطية',
                'district' => 'الجلفة وسط',
                'phone' => '0662797990',
                'manager_name' => 'شامخ عبد الله',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'ريكي مصطفى',
                'district' => 'الجلفة وسط',
                'phone' => '027937258',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'زريعة عبد القادر',
                'district' => 'الجلفة وسط',
                'phone' => '027926857',
                'manager_name' => 'طاهري عبد القادر',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'زيتوني بلخير(عبيكشي السعيد)',
                'district' => 'الجلفة وسط',
                'phone' => '0777583159',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'شلالي يوسف',
                'district' => 'الجلفة وسط',
                'phone' => '0663989277',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'صويلح شويحة',
                'district' => 'الجلفة وسط',
                'phone' => '0676155547',
                'manager_name' => 'جناد لعموري',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'صيلع بلخير',
                'district' => 'الجلفة وسط',
                'phone' => '0668674102',
                'manager_name' => 'بن غربي دلال',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'عبيدي ربيح',
                'district' => 'الجلفة وسط',
                'phone' => '0698521089',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'غربي بن عبد الله',
                'district' => 'الجلفة وسط',
                'phone' => '0656170746',
                'manager_name' => '/',
                'student_count' => 400,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'فيلالي البشير',
                'district' => 'الجلفة وسط',
                'phone' => '0699250342',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'قاسم سليمان',
                'district' => 'الجلفة وسط',
                'phone' => '0655454081',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'قشام الميلود',
                'district' => 'الجلفة وسط',
                'phone' => '0662728015',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'قوريدة احمد',
                'district' => 'الجلفة وسط',
                'phone' => '0671481925',
                'manager_name' => '/',
                'student_count' => 460,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'كاس محمد',
                'district' => 'الجلفة وسط',
                'phone' => '0699515076',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'لباز مصطفى',
                'district' => 'الجلفة وسط',
                'phone' => '0663452225',
                'manager_name' => 'فيتس سليمة',
                'student_count' => 300,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'لقرادة بلقاسم',
                'district' => 'الجلفة وسط',
                'phone' => '0657784078',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'محفوظي عمر',
                'district' => 'الجلفة وسط',
                'phone' => '0660777759',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'مقواس عمر',
                'district' => 'الجلفة وسط',
                'phone' => '0771373127',
                'manager_name' => 'امحمد بن صالح بودانة',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'شيبوط بلعباس',
                'district' => 'الجلفة وسط',
                'phone' => '0697357585',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'نعاس عبد الحميد',
                'district' => 'الجلفة وسط',
                'phone' => '0659618131',
                'manager_name' => 'قطوش مصطفى',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'ونوقي احمد',
                'district' => 'الجلفة وسط',
                'phone' => '0658946050',
                'manager_name' => 'جعفر محمد',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            
            // Commune: حاسي بحبح
            [
                'name' => 'الامير عبد القادر',
                'district' => 'حاسي بحبح',
                'phone' => '0776501720',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'العطري بن عرعار',
                'district' => 'حاسي بحبح',
                'phone' => '0783175153',
                'manager_name' => 'بن تشيش مصطفى',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'القدس',
                'district' => 'حاسي بحبح',
                'phone' => '0775151786',
                'manager_name' => '/',
                'student_count' => 300,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بختي عطية',
                'district' => 'حاسي بحبح',
                'phone' => '0657702749',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بشار بن جدو',
                'district' => 'حاسي بحبح',
                'phone' => '0672691893',
                'manager_name' => '/',
                'student_count' => 470,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بلحرش عبد الله',
                'district' => 'حاسي بحبح',
                'phone' => '0664478780',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بن حنة بلعارية',
                'district' => 'حاسي بحبح',
                'phone' => '0771648980',
                'manager_name' => '/',
                'student_count' => 300,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بن خيره محمد',
                'district' => 'حاسي بحبح',
                'phone' => '0676966026',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بن دنيدينة محمد',
                'district' => 'حاسي بحبح',
                'phone' => '0698717205',
                'manager_name' => 'زروقي الحاج',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بن يطو عبد القادر',
                'district' => 'حاسي بحبح',
                'phone' => '027954094',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بهناس عطية',
                'district' => 'حاسي بحبح',
                'phone' => '0770107245',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بويح محمد',
                'district' => 'حاسي بحبح',
                'phone' => '0664191916',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'حمداني المداني',
                'district' => 'حاسي بحبح',
                'phone' => '0666336986',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'خلاص اسماعيل',
                'district' => 'حاسي بحبح',
                'phone' => '0664787582',
                'manager_name' => 'تفاح رابح',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'سيدي نايل',
                'district' => 'حاسي بحبح',
                'phone' => '027961855',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'شتوح الطيب',
                'district' => 'حاسي بحبح',
                'phone' => '0783281243',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'عبد الرحيم بلعباس',
                'district' => 'حاسي بحبح',
                'phone' => '027964132',
                'manager_name' => 'دوارة سالم',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'عمر ادريس',
                'district' => 'حاسي بحبح',
                'phone' => '0667729080',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'قارف احمد',
                'district' => 'حاسي بحبح',
                'phone' => '0780770811',
                'manager_name' => '/',
                'student_count' => 471,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'قاسمي الحاج',
                'district' => 'حاسي بحبح',
                'phone' => '0668175980',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'قوق سليمان',
                'district' => 'حاسي بحبح',
                'phone' => '0770348848',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'كربوعة سالم',
                'district' => 'حاسي بحبح',
                'phone' => '0665810807',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'مبخوتة احمد',
                'district' => 'حاسي بحبح',
                'phone' => '0673546793',
                'manager_name' => 'سايب خديجة',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'محمد بوضياف',
                'district' => 'حاسي بحبح',
                'phone' => '027950337',
                'manager_name' => '/',
                'student_count' => 320,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'مغربي الحاج',
                'district' => 'حاسي بحبح',
                'phone' => '0662600976',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'نقبيل عبد القادر',
                'district' => 'حاسي بحبح',
                'phone' => '0662709905',
                'manager_name' => 'غراب عبد القادر',
                'student_count' => 320,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'نوع د',
                'district' => 'حاسي بحبح',
                'phone' => '0697909906',
                'manager_name' => '/',
                'student_count' => 525,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'نوي احمد',
                'district' => 'حاسي بحبح',
                'phone' => '0667757975',
                'manager_name' => 'شراك لخضر',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'هزرشي بن صيفية',
                'district' => 'حاسي بحبح',
                'phone' => '0776073666',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            
            // Commune: عين وسارة
            [
                'name' => 'الاخضر بوضياف',
                'district' => 'عين وسارة',
                'phone' => '0778186818',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'الامير عبد القادر',
                'district' => 'عين وسارة',
                'phone' => '0664152333',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'البشير الابراهيمي',
                'district' => 'عين وسارة',
                'phone' => '0777636119',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'العربي بن مهدي',
                'district' => 'عين وسارة',
                'phone' => '0797066363',
                'manager_name' => 'لعموري عبد العزيز',
                'student_count' => 420,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'العقيد لطفي',
                'district' => 'عين وسارة',
                'phone' => '0672523076',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'اوقيس مرباع',
                'district' => 'عين وسارة',
                'phone' => '027807309',
                'manager_name' => 'كريفيف صالح',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'اول نوفمبر 54',
                'district' => 'عين وسارة',
                'phone' => '0699935021',
                'manager_name' => '/',
                'student_count' => 145,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'بن مهل فارس',
                'district' => 'عين وسارة',
                'phone' => '0696265771',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'جعفور محمد بوعمامة',
                'district' => 'عين وسارة',
                'phone' => '0780257966',
                'manager_name' => '/',
                'student_count' => 300,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'جغمة رمضان',
                'district' => 'عين وسارة',
                'phone' => '0772390823',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'حيرش عبد القادر',
                'district' => 'عين وسارة',
                'phone' => '0793478818',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'خديجة ام المؤمنين',
                'district' => 'عين وسارة',
                'phone' => '0664160563',
                'manager_name' => '/',
                'student_count' => 400,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'دهيليس احمد',
                'district' => 'عين وسارة',
                'phone' => '0791510592',
                'manager_name' => 'عبد الحميد شداد',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'ربحي العيهار',
                'district' => 'عين وسارة',
                'phone' => '0777692156',
                'manager_name' => 'نية دردوري',
                'student_count' => 420,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'رحماني محمد يحي',
                'district' => 'عين وسارة',
                'phone' => '0772035311',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'زايدي داود القدس',
                'district' => 'عين وسارة',
                'phone' => '0777962329',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'شراف سعد',
                'district' => 'عين وسارة',
                'phone' => '0773306282',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'طرشون مصطفى',
                'district' => 'عين وسارة',
                'phone' => '0778858490',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'طيباوي المسعود',
                'district' => 'عين وسارة',
                'phone' => '0771659591',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'عائشة ام المؤمنين',
                'district' => 'عين وسارة',
                'phone' => '0774657682',
                'manager_name' => 'قوطارة دحمان',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'عبد الحميد قمورة',
                'district' => 'عين وسارة',
                'phone' => '0771865045',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'عقبة بن نافع',
                'district' => 'عين وسارة',
                'phone' => '0772364683',
                'manager_name' => 'قاسمي',
                'student_count' => 460,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'عمران الطيب',
                'district' => 'عين وسارة',
                'phone' => '0791569877',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'غول محمد',
                'district' => 'عين وسارة',
                'phone' => '0659611075',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'غويني عبد القادر',
                'district' => 'عين وسارة',
                'phone' => '027945154',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'فرحات الطيب',
                'district' => 'عين وسارة',
                'phone' => '0770202590',
                'manager_name' => '/',
                'student_count' => 617,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'فرحات مرهون',
                'district' => 'عين وسارة',
                'phone' => '0774743154',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'قاسمي الحسني احمد',
                'district' => 'عين وسارة',
                'phone' => '0671085755',
                'manager_name' => 'الصيد حيزية',
                'student_count' => 376,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'قداش السعيد',
                'district' => 'عين وسارة',
                'phone' => '0792400592',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'لحرش مصطفى',
                'district' => 'عين وسارة',
                'phone' => '0666110690',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'لحول الميلود',
                'district' => 'عين وسارة',
                'phone' => '0669330188',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'لمين عبد الرحيم',
                'district' => 'عين وسارة',
                'phone' => '0797971233',
                'manager_name' => 'حزي لخضر',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'مالك بن النبي',
                'district' => 'عين وسارة',
                'phone' => '027802421',
                'manager_name' => '/',
                'student_count' => 488,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'محمدالصديق بن يحي',
                'district' => 'عين وسارة',
                'phone' => '0666539056',
                'manager_name' => '/',
                'student_count' => 230,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'محمد العيد ال خليفة',
                'district' => 'عين وسارة',
                'phone' => '0771586635',
                'manager_name' => 'بن كردو كريمة',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'مرباح محمد بن الدكاني',
                'district' => 'عين وسارة',
                'phone' => '0773007261',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'مريسي محمد',
                'district' => 'عين وسارة',
                'phone' => '027807083',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'مصطفاي بلقاسم',
                'district' => 'عين وسارة',
                'phone' => '0673010202',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'معمري محمد',
                'district' => 'عين وسارة',
                'phone' => '0774937804',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'نايب محمد',
                'district' => 'عين وسارة',
                'phone' => '0699545016',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'نقزيو ساعد',
                'district' => 'عين وسارة',
                'phone' => '0667783761',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'نوع د 400 مسكن 600مسكن',
                'district' => 'عين وسارة',
                'phone' => '0770630112',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'الجلفة',
            ],
            [
                'name' => 'يحي بن علية',
                'district' => 'عين وسارة',
                'phone' => '0790028517',
                'manager_name' => '/',
                'student_count' => 207,
                'wilaya' => 'الجلفة',
            ],
        ];
        
        foreach ($schools as $school) {
            School::create($school);
        }
    }
    
    /**
     * Wilaya: اولاد جلال (Ouled Djellal)
     */
    private function importOuledDjellalSchools(): void
    {
        $this->command->info('Importation des écoles de اولاد جلال...');
        
        $schools = [
            // Commune: البسباس
            [
                'name' => 'بار عمر بن عبد الرحمان',
                'district' => 'البسباس',
                'phone' => '0672698825',
                'manager_name' => 'خنيفر علي',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'هاني محمد',
                'district' => 'البسباس',
                'phone' => '0671135867',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            
            // Commune: الدوسن
            [
                'name' => 'بوبكر مبروك',
                'district' => 'الدوسن',
                'phone' => '0666106644',
                'manager_name' => 'الزهرة يوب',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'بوساحة المداني',
                'district' => 'الدوسن',
                'phone' => '0797972234',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'تفة محمد',
                'district' => 'الدوسن',
                'phone' => '0662146259',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'سكال حسين',
                'district' => 'الدوسن',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'شايب ذراع علي',
                'district' => 'الدوسن',
                'phone' => '033677281',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'لكحل محمد',
                'district' => 'الدوسن',
                'phone' => '0793999589',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'مصمودي محمد',
                'district' => 'الدوسن',
                'phone' => '0552050860',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            
            // Commune: اولاد جلال وسط
            [
                'name' => 'العقيد لطفي',
                'district' => 'اولاد جلال وسط',
                'phone' => '0663513720',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'الغول ابراهيم',
                'district' => 'اولاد جلال وسط',
                'phone' => '0663894989',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'اولاد موسى العربي',
                'district' => 'اولاد جلال وسط',
                'phone' => '0664129257',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'زنودة مصطفى',
                'district' => 'اولاد جلال وسط',
                'phone' => '033560201',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'سماتي محمد بلعابد',
                'district' => 'اولاد جلال وسط',
                'phone' => '033662858',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'سي مزراق بلقاسم',
                'district' => 'اولاد جلال وسط',
                'phone' => '0662157556',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'شخشوخ عبد الرحمان',
                'district' => 'اولاد جلال وسط',
                'phone' => '0669886307',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'شلاغة الحشاني',
                'district' => 'اولاد جلال وسط',
                'phone' => '0676284030',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'شنوفي الشريف',
                'district' => 'اولاد جلال وسط',
                'phone' => '0673643837',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'عبدالحميد بن باديس',
                'district' => 'اولاد جلال وسط',
                'phone' => '0698140411',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'فردوس عبد اللطيف',
                'district' => 'اولاد جلال وسط',
                'phone' => '0671365318',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'لكحل مختار',
                'district' => 'اولاد جلال وسط',
                'phone' => '0663788900',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'مفدي زكرياء',
                'district' => 'اولاد جلال وسط',
                'phone' => '0668512413',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'مواق مسعود',
                'district' => 'اولاد جلال وسط',
                'phone' => '033662233',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            
            // Commune: سيدي خالد
            [
                'name' => 'بن محياوي محمد',
                'district' => 'سيدي خالد',
                'phone' => '0666911498',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'بودرهم محمد',
                'district' => 'سيدي خالد',
                'phone' => '0662754884',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'بوطي لخضر',
                'district' => 'سيدي خالد',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'جهرة الشيخ',
                'district' => 'سيدي خالد',
                'phone' => '0668607124',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'حويلي بلعباسي',
                'district' => 'سيدي خالد',
                'phone' => '033669543',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'دريسي عبد الغفار',
                'district' => 'سيدي خالد',
                'phone' => '0660460978',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'زهانة لزهاري',
                'district' => 'سيدي خالد',
                'phone' => '0665961133',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'عبد الحميد بن باديس',
                'district' => 'سيدي خالد',
                'phone' => '033672151',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'مدلل محمد',
                'district' => 'سيدي خالد',
                'phone' => '0664639423',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
            [
                'name' => 'هاني محمد',
                'district' => 'سيدي خالد',
                'phone' => '0664934064',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'اولاد جلال',
            ],
        ];
        
        foreach ($schools as $school) {
            School::create($school);
        }
    }
    
    /**
     * Wilaya: بسكرة (Biskra)
     */
    private function importBiskraSchools(): void
    {
        $this->command->info('Importation des écoles de بسكرة...');
        
        $schools = [
            // Commune: اوماش
            [
                'name' => 'بوخاري',
                'district' => 'اوماش',
                'phone' => '0668180430',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'عليمي عبد الله',
                'district' => 'اوماش',
                'phone' => '0663518887',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: البرانيس
            [
                'name' => 'اولاد الصيد',
                'district' => 'البرانيس',
                'phone' => '0661765839',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'محمد مرزوق',
                'district' => 'البرانيس',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: برج بن عزوز
            [
                'name' => 'الاخوة شمة',
                'district' => 'برج بن عزوز',
                'phone' => '0552500010',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'بجاوي الطاهر',
                'district' => 'برج بن عزوز',
                'phone' => '0770658064',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'بن خرف الله مسعود',
                'district' => 'برج بن عزوز',
                'phone' => '0558253110',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'عبادو لخضر',
                'district' => 'برج بن عزوز',
                'phone' => '0779251021',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: بسكرة وسط
            [
                'name' => '17اكتوبر',
                'district' => 'بسكرة وسط',
                'phone' => '0663398972',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'ابن باديس',
                'district' => 'بسكرة وسط',
                'phone' => '0699092241',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'احمد فارح',
                'district' => 'بسكرة وسط',
                'phone' => '0671955481',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'بركات العرافي',
                'district' => 'بسكرة وسط',
                'phone' => '0663543095',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'بن النوي حركات',
                'district' => 'بسكرة وسط',
                'phone' => '0778047682',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'بن مالك بن لحسن',
                'district' => 'بسكرة وسط',
                'phone' => '0770297458',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'بن ومان المداني',
                'district' => 'بسكرة وسط',
                'phone' => '0671461443',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'بوستة محمد',
                'district' => 'بسكرة وسط',
                'phone' => '0772334477',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'تمامي لخضر',
                'district' => 'بسكرة وسط',
                'phone' => '0793584393',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'جهرة الحفناوي',
                'district' => 'بسكرة وسط',
                'phone' => '0659777694',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'حبة عبد المجيد',
                'district' => 'بسكرة وسط',
                'phone' => '0663999800',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'حسين قصباية',
                'district' => 'بسكرة وسط',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'حمودي بولرباح',
                'district' => 'بسكرة وسط',
                'phone' => '0655746338',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'خباش عبد الحميد',
                'district' => 'بسكرة وسط',
                'phone' => '0779087042',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'خراشي احمد',
                'district' => 'بسكرة وسط',
                'phone' => '0666934233',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'خليفة احمد',
                'district' => 'بسكرة وسط',
                'phone' => '0657206287',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'دبابش سيف الدين',
                'district' => 'بسكرة وسط',
                'phone' => '0662334131',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'دبابش عبد الله',
                'district' => 'بسكرة وسط',
                'phone' => '0662717035',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'دبابش لزهاري',
                'district' => 'بسكرة وسط',
                'phone' => '0699633016',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'دراجي عمار',
                'district' => 'بسكرة وسط',
                'phone' => '0671711352',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'رقاز محمد',
                'district' => 'بسكرة وسط',
                'phone' => '0660677935',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'زيادي زيادي',
                'district' => 'بسكرة وسط',
                'phone' => '0667426016',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'سعادة ابراهيم',
                'district' => 'بسكرة وسط',
                'phone' => '0671535180',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'سي العابدي مهنية',
                'district' => 'بسكرة وسط',
                'phone' => '0662434579',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'سيدهم ميلود',
                'district' => 'بسكرة وسط',
                'phone' => '0797520814',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'صاولي الشريف',
                'district' => 'بسكرة وسط',
                'phone' => '0795402205',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'طبش محمد',
                'district' => 'بسكرة وسط',
                'phone' => '0669306771',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'علواني عبد الحميد',
                'district' => 'بسكرة وسط',
                'phone' => '0673790157',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'عيسى واعر',
                'district' => 'بسكرة وسط',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'قرين بشير',
                'district' => 'بسكرة وسط',
                'phone' => '0774417330',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'لخضر بن كريبع',
                'district' => 'بسكرة وسط',
                'phone' => '0699027131',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'لهلالي زميط',
                'district' => 'بسكرة وسط',
                'phone' => '0659862191',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'مبارك العنابي',
                'district' => 'بسكرة وسط',
                'phone' => '0662708209',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'مرزوق لخضر',
                'district' => 'بسكرة وسط',
                'phone' => '0773219280',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'مزياني العيد',
                'district' => 'بسكرة وسط',
                'phone' => '0655248357',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'مزياني عمر',
                'district' => 'بسكرة وسط',
                'phone' => '0777708243',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'ميرة السعيد',
                'district' => 'بسكرة وسط',
                'phone' => '0675348253',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'هراكي لخضر',
                'district' => 'بسكرة وسط',
                'phone' => '0665993322',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'يكن الهادي',
                'district' => 'بسكرة وسط',
                'phone' => '0663743544',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: بوشقرون
            [
                'name' => 'مغزي حب الله',
                'district' => 'بوشقرون',
                'phone' => '0669160069',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: جمورة
            [
                'name' => 'برباري الصادق',
                'district' => 'جمورة',
                'phone' => '0696799562',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'برباري العربي',
                'district' => 'جمورة',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'زرقان علي',
                'district' => 'جمورة',
                'phone' => '0662709595',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'سايحي بلقاسم',
                'district' => 'جمورة',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'فراس سعيد',
                'district' => 'جمورة',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: سيدي عقبة
            [
                'name' => 'احمد رضا حوحو',
                'district' => 'سيدي عقبة',
                'phone' => '0662385400',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'بن خلف الله موفق',
                'district' => 'سيدي عقبة',
                'phone' => '0666675473',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'تبينة علي',
                'district' => 'سيدي عقبة',
                'phone' => '0660166406',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'خطاب عبد الحفيظ',
                'district' => 'سيدي عقبة',
                'phone' => '0671139489',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'رقيق بشير',
                'district' => 'سيدي عقبة',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'سعدية دراجي',
                'district' => 'سيدي عقبة',
                'phone' => '0675436357',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'سلطاني عمر',
                'district' => 'سيدي عقبة',
                'phone' => '0698790361',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'مسعودي اسماعيل',
                'district' => 'سيدي عقبة',
                'phone' => '0663521293',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'مسعودي سبع',
                'district' => 'سيدي عقبة',
                'phone' => '0675737405',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'مسعودي مصطفى',
                'district' => 'سيدي عقبة',
                'phone' => '0660850057',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: شتمة
            [
                'name' => 'طالبي مختار',
                'district' => 'شتمة',
                'phone' => '0675159142',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'عباس نطار',
                'district' => 'شتمة',
                'phone' => '0778082351',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'نحوي محمد',
                'district' => 'شتمة',
                'phone' => '0674972609',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: طولقة
            [
                'name' => 'احمد محبوب',
                'district' => 'طولقة',
                'phone' => '0698474034',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'السايب معمر',
                'district' => 'طولقة',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'النهضة',
                'district' => 'طولقة',
                'phone' => '0559059368',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'بن بلعباس',
                'district' => 'طولقة',
                'phone' => '0668421330',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'بوشامي محمد',
                'district' => 'طولقة',
                'phone' => '0699966630',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'حسين علي',
                'district' => 'طولقة',
                'phone' => '0656696171',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'حشاني الدراجي',
                'district' => 'طولقة',
                'phone' => '0772519991',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'حملاوي عامر',
                'district' => 'طولقة',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'حمود مسعود',
                'district' => 'طولقة',
                'phone' => '0779004280',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'حميدي عيسى',
                'district' => 'طولقة',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'ساعد مخلوف',
                'district' => 'طولقة',
                'phone' => '0675761607',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'سلمي محمد الصغير',
                'district' => 'طولقة',
                'phone' => '0668421184',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'شريف مواقي',
                'district' => 'طولقة',
                'phone' => '0782003327',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'شكري محمد',
                'district' => 'طولقة',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'شوراب احمد',
                'district' => 'طولقة',
                'phone' => '0793645058',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'عطية مداني',
                'district' => 'طولقة',
                'phone' => '0793399318',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'قانة صميدة',
                'district' => 'طولقة',
                'phone' => '0774162401',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'قيصران احمد',
                'district' => 'طولقة',
                'phone' => '0775941468',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'محمد العيد ال خليفة',
                'district' => 'طولقة',
                'phone' => '0555269441',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: فوغالة
            [
                'name' => 'ضحوي محمد',
                'district' => 'فوغالة',
                'phone' => '0779155150',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'غنانية مسعود',
                'district' => 'فوغالة',
                'phone' => '0676497636',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'نقنوق عثمان',
                'district' => 'فوغالة',
                'phone' => '0781815279',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: لغروس
            [
                'name' => 'احمد طالب',
                'district' => 'لغروس',
                'phone' => '0781398989',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'الوافي لزهاري',
                'district' => 'لغروس',
                'phone' => '0772474069',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'ضيفلي صالح',
                'district' => 'لغروس',
                'phone' => '0774866047',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'صالح الهامل',
                'district' => 'لغروس',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: لوطاية
            [
                'name' => 'بحمة علي',
                'district' => 'لوطاية',
                'phone' => '0665152161',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: ليشانة
            [
                'name' => 'بن صغير بوزيان',
                'district' => 'ليشانة',
                'phone' => '0792889998',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'شيخ بوزيان',
                'district' => 'ليشانة',
                'phone' => '0559226139',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'لمعافي عبد الباقي',
                'district' => 'ليشانة',
                'phone' => '0791575494',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: ليوة
            [
                'name' => 'رحاب العرافي',
                'district' => 'ليوة',
                'phone' => '0549721546',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'صيد محمد',
                'district' => 'ليوة',
                'phone' => '0561598444',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'طواهرية سليمان',
                'district' => 'ليوة',
                'phone' => '0542189264',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'قروج الجموعي',
                'district' => 'ليوة',
                'phone' => '0671173618',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            
            // Commune: مشونش
            [
                'name' => 'حسين عبد الباقي',
                'district' => 'مشونش',
                'phone' => '0671731045',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'زير بشير',
                'district' => 'مشونش',
                'phone' => '0663550218',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'سي الحواس',
                'district' => 'مشونش',
                'phone' => '0660905154',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
            [
                'name' => 'صالح عمراوي',
                'district' => 'مشونش',
                'phone' => '0671534449',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'بسكرة',
            ],
        ];
        
        foreach ($schools as $school) {
            School::create($school);
        }
    }
    
    /**
     * Wilaya: سكيكدة (Skikda)
     * Note: Due to size, I'm including a subset. You can expand this as needed.
     */
    private function importSkikdaSchools(): void
    {
        $this->command->info('Importation des écoles de سكيكدة (subset)...');
        
        $schools = [
            // Commune: الحدائق
            [
                'name' => 'احمد سلطان',
                'district' => 'الحدائق',
                'phone' => '0660616400',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'سكيكدة',
            ],
            [
                'name' => 'الاخوة شناف',
                'district' => 'الحدائق',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'سكيكدة',
            ],
            [
                'name' => 'الأمير عبد القادر',
                'district' => 'الحدائق',
                'phone' => '0793389953',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'سكيكدة',
            ],
            [
                'name' => 'بولقطوط صالح',
                'district' => 'الحدائق',
                'phone' => '0790863730',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'سكيكدة',
            ],
            // Add more schools as needed...
        ];
        
        foreach ($schools as $school) {
            School::create($school);
        }
    }
    
    /**
     * Wilaya: عنابة (Annaba)
     */
    private function importAnnabaSchools(): void
    {
        $this->command->info('Importation des écoles de عنابة (subset)...');
        
        $schools = [
            // Commune: احمد بوقصاص
            [
                'name' => 'صالح بوتريبة',
                'district' => 'احمد بوقصاص',
                'phone' => '0669292089',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'عنابة',
            ],
            [
                'name' => 'معنصري امحمد',
                'district' => 'احمد بوقصاص',
                'phone' => '0675296721',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'عنابة',
            ],
            // Add more schools as needed...
        ];
        
        foreach ($schools as $school) {
            School::create($school);
        }
    }
    
    /**
     * Wilaya: قسنطينة (Constantine)
     */
    private function importConstantineSchools(): void
    {
        $this->command->info('Importation des écoles de قسنطينة (subset)...');
        
        $schools = [
            // Commune: الخروب
            [
                'name' => 'ابن رشد',
                'district' => 'الخروب',
                'phone' => '0658402248',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'قسنطينة',
            ],
            [
                'name' => 'احمد بوشبعة',
                'district' => 'الخروب',
                'phone' => '0550689308',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'قسنطينة',
            ],
            // Add more schools as needed...
        ];
        
        foreach ($schools as $school) {
            School::create($school);
        }
    }
    
    /**
     * Wilaya: لمغير (El M\'Ghair)
     */
    private function importElMghairSchools(): void
    {
        $this->command->info('Importation des écoles de لمغير...');
        
        $schools = [
            // Commune: أم الطيور
            [
                'name' => 'الوئام المدني',
                'district' => 'أم الطيور',
                'phone' => '0665093352',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'حي 19مارس',
                'district' => 'أم الطيور',
                'phone' => '0670438019',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'زاوي موسى',
                'district' => 'أم الطيور',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'شهرة موسى',
                'district' => 'أم الطيور',
                'phone' => '0780231821',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'عماري العيد',
                'district' => 'أم الطيور',
                'phone' => '0780414472',
                'manager_name' => 'جمال بالمهدي',
                'student_count' => 435,
                'wilaya' => 'لمغير',
            ],
            
            // Commune: انسيغة
            [
                'name' => 'براشد عبد الرزاق',
                'district' => 'انسيغة',
                'phone' => '0780341377',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'دغوش صالح',
                'district' => 'انسيغة',
                'phone' => '0698621303',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'خالدي عيسى',
                'district' => 'انسيغة',
                'phone' => '0780424039',
                'manager_name' => 'سعد الله عيدية',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'مداس عمر',
                'district' => 'انسيغة',
                'phone' => '0792188757',
                'manager_name' => 'براهيمي عثمان',
                'student_count' => 182,
                'wilaya' => 'لمغير',
            ],
            
            // Commune: تنديلة
            [
                'name' => 'الاخوة سلطاني',
                'district' => 'تنديلة',
                'phone' => '0780578343',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'رحماني محمد',
                'district' => 'تنديلة',
                'phone' => '0663496073',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'سلطاني التهامي',
                'district' => 'تنديلة',
                'phone' => '0663259095',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'سلطاني عبد القادر',
                'district' => 'تنديلة',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            
            // Commune: جامعة
            [
                'name' => 'بريالة بلقاسم',
                'district' => 'جامعة',
                'phone' => '0780236071',
                'manager_name' => 'بالطاهر مبروك بن احمد',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'بلعقون صالح',
                'district' => 'جامعة',
                'phone' => '0659926740',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'بن عوالي عبد الرزاق',
                'district' => 'جامعة',
                'phone' => '0662969883',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'بن مبروك محمد',
                'district' => 'جامعة',
                'phone' => '0780402027',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'بن نونة الدراجي',
                'district' => 'جامعة',
                'phone' => '0780254493',
                'manager_name' => 'عزوك مختار',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'بوحفص الحاج',
                'district' => 'جامعة',
                'phone' => '0667369833',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'بوحنية احمد',
                'district' => 'جامعة',
                'phone' => '0699365089',
                'manager_name' => 'منير',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'بوعنان بلقاسم',
                'district' => 'جامعة',
                'phone' => '0780294132',
                'manager_name' => '/',
                'student_count' => 347,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'رحماني ام الخير',
                'district' => 'جامعة',
                'phone' => '0666571641',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'سوالمي الجموعي',
                'district' => 'جامعة',
                'phone' => '0782271111',
                'manager_name' => 'هشام دودو',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'عماري محمد',
                'district' => 'جامعة',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'قادري عبد السلام',
                'district' => 'جامعة',
                'phone' => '0780454541',
                'manager_name' => 'أيش محمد لسعد',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'قطار مسعود',
                'district' => 'جامعة',
                'phone' => '0662257037',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'مجمع حي الحبل',
                'district' => 'جامعة',
                'phone' => '0780236512',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'مجمع علوشة',
                'district' => 'جامعة',
                'phone' => '0780253217',
                'manager_name' => 'زكاري تماسيني',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'مسعودي علي',
                'district' => 'جامعة',
                'phone' => '0782137045',
                'manager_name' => 'لخضر ناصر',
                'student_count' => 518,
                'wilaya' => 'لمغير',
            ],
            
            // Commune: سطيل
            [
                'name' => 'برابح اسماعيل',
                'district' => 'سطيل',
                'phone' => '0665848381',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'رميثة محمد',
                'district' => 'سطيل',
                'phone' => '0780609125',
                'manager_name' => 'محمد جروبي',
                'student_count' => 384,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'غشة صالح',
                'district' => 'سطيل',
                'phone' => '0662424640',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            
            // Commune: سيدي خليل
            [
                'name' => 'العربي بن مهيدي1',
                'district' => 'سيدي خليل',
                'phone' => '0664167393',
                'manager_name' => 'معمر بالطاهر',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'العربي بن مهيدي2',
                'district' => 'سيدي خليل',
                'phone' => '0666504989',
                'manager_name' => 'بن قلية احمد',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'بالبابح علي',
                'district' => 'سيدي خليل',
                'phone' => '0782898755',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'سويسي محمد',
                'district' => 'سيدي خليل',
                'phone' => '0696519769',
                'manager_name' => 'زوبيري الزهرة',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            
            // Commune: سيدي عمران
            [
                'name' => '05جويلية1962',
                'district' => 'سيدي عمران',
                'phone' => null,
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'جلالي عيسى',
                'district' => 'سيدي عمران',
                'phone' => '0780206849',
                'manager_name' => 'الأخضر بوليف',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'حمادي حسين',
                'district' => 'سيدي عمران',
                'phone' => '0660135881',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'حملاوي ابراهيم',
                'district' => 'سيدي عمران',
                'phone' => '0780453366',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'ريزوق بشير',
                'district' => 'سيدي عمران',
                'phone' => '0780261851',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'عمراني علي',
                'district' => 'سيدي عمران',
                'phone' => '0782272405',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'فضل السعيد',
                'district' => 'سيدي عمران',
                'phone' => '0780338006',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            
            // Commune: لمغير وسط
            [
                'name' => 'العربي التبسي',
                'district' => 'لمغير وسط',
                'phone' => '0780223480',
                'manager_name' => '/',
                'student_count' => 691,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'العقيد سي الحواس',
                'district' => 'لمغير وسط',
                'phone' => '0541530081',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'باسو لخضر',
                'district' => 'لمغير وسط',
                'phone' => '0663362830',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'بربيع محمد',
                'district' => 'لمغير وسط',
                'phone' => '0780217810',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'بركة موسى',
                'district' => 'لمغير وسط',
                'phone' => '0782295724',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'برمكي عيسى',
                'district' => 'لمغير وسط',
                'phone' => '0780349450',
                'manager_name' => 'امحمد دباخ',
                'student_count' => 517,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'جابو عبد الرحمان',
                'district' => 'لمغير وسط',
                'phone' => '0780343995',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'جروني رابح',
                'district' => 'لمغير وسط',
                'phone' => '0770538223',
                'manager_name' => 'موسى الصغير',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'جريبيع عمر',
                'district' => 'لمغير وسط',
                'phone' => '0780233610',
                'manager_name' => 'سعد دهنون',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'دباخ علي',
                'district' => 'لمغير وسط',
                'phone' => '0780553131',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'شهرة موسى',
                'district' => 'لمغير وسط',
                'phone' => '0780475105',
                'manager_name' => '/',
                'student_count' => 431,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'عائشة ام المؤمنين',
                'district' => 'لمغير وسط',
                'phone' => '0780412191',
                'manager_name' => 'كمال بالمهدي',
                'student_count' => 400,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'علي خليل',
                'district' => 'لمغير وسط',
                'phone' => '0699716469',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'قيدوس احمد',
                'district' => 'لمغير وسط',
                'phone' => '0780339158',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
            [
                'name' => 'لهرم محمد',
                'district' => 'لمغير وسط',
                'phone' => '0780411814',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'لمغير',
            ],
        ];
        
        foreach ($schools as $school) {
            School::create($school);
        }
    }
    
    /**
     * Wilaya: مسيلة (M'Sila)
     * Note: This is a subset due to size constraints
     */
    private function importMSilaSchools(): void
    {
        $this->command->info('Importation des écoles de مسيلة (subset)...');
        
        $schools = [
            // Commune: المعاضيد
            [
                'name' => 'الطيب بن عمر',
                'district' => 'المعاضيد',
                'phone' => '0555641041',
                'manager_name' => '/',
                'student_count' => 0,
                'wilaya' => 'مسيلة',
            ],
            [
                'name' => 'اول نوفمبر54',
                'district' => 'المعاضيد',
                'phone' => '0660506091',
                'manager_name' => '/',
                'student_count' => 482,
                'wilaya' => 'مسيلة',
            ],
            // Add more schools as needed...
        ];
        
        foreach ($schools as $school) {
            School::create($school);
        }
    }
    
    /**
     * Wilaya: ميلة (Mila)
     * Note: This is a subset due to size constraints
     */
    private function importMilaSchools(): void
    {
        $this->command->info('Importation des écoles de ميلة (subset)...');
        
        $schools = [
            // Commune: احمد راشدي
            [
                'name' => 'دباش بشير',
                'district' => 'احمد راشدي',
                'phone' => '0791883681',
                'manager_name' => '/',
                'student_count' => 567,
                'wilaya' => 'ميلة',
            ],
            [
                'name' => 'سعداوي علاوة',
                'district' => 'احمد راشدي',
                'phone' => '0699356576',
                'manager_name' => '/',
                'student_count' => 273,
                'wilaya' => 'ميلة',
            ],
            // Add more schools as needed...
        ];
        
        foreach ($schools as $school) {
            School::create($school);
        }
    }
}