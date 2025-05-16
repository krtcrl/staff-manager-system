<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PartsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('parts')->delete();
        
        \DB::table('parts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'part_number' => '184619-001',
                'part_name' => 'BI-TECH; 122-4286-49-2A; Issue 6; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'part_number' => '193129-001',
                'part_name' => 'Grupo Antolin; 172655000-149; BMW Light Engine_NEW LED; Double-Sided; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'part_number' => '189255-001',
                'part_name' => 'CONTINENTAL AUTOMOTIVE 12 LED new copper',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'part_number' => '175182-001',
                'part_name' => 'DELPHI 2698',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'part_number' => '192575-001',
            'part_name' => 'MERIT 27274 REV D (STIFFENER)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'part_number' => '192576-001',
            'part_name' => 'MERIT 27275 REV C; (STIFFENER)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'part_number' => '186514-001',
                'part_name' => 'DELTA CONTROLS ANTENNA',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'part_number' => '181001-001',
                'part_name' => 'EBW LH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'part_number' => '181003-001',
                'part_name' => 'EBW RH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'part_number' => '183466-001',
                'part_name' => 'EBW ELECTRONICS; U540 SAE NEW PARTNUMBER',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'part_number' => '183468-001',
                'part_name' => 'EBW ELECTRONICS; U540 ECE NEW PARTNUMBER',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'part_number' => '186946-001',
                'part_name' => 'EBW LH; DURANGO DRL LH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'part_number' => '186950-001',
                'part_name' => 'EBW RH; DURANGO DRL RH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'part_number' => '190650-001',
                'part_name' => 'IC;SUB;EBW;14945; D; LH INBOARD;BLANKED;NTPI',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'part_number' => '190655-001',
                'part_name' => 'IC;SUB;EBW;14944; D; LH OUTBOARD;BLANKED;NTPI',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'part_number' => '190661-001',
                'part_name' => 'IC;SUB;EBW;15145; D; RH; INBOARD;BLANKED;NTPI',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'part_number' => '190666-001',
                'part_name' => 'IC;SUB;EBW;15144; D; RH; OUTBOARD;BLANKED;NTPI',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'part_number' => '191102-001',
                'part_name' => 'EBW;15757; D2; LH;BT1FG RCL LH STT; 14945 & 14944 ASSY TO PLATE;NTPI',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'part_number' => '191103-001',
                'part_name' => 'IC;CKT;EBW;15758; D2; RH; BT1FG RCL RH STT; 15145 & 15144 ASSY TO PLATE;NTPI',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'part_number' => '191337-001',
                'part_name' => 'IC SUB; EBW; 15849; C; LH STT; BT1UG RCL; INBOARD SUB; BLANKED; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'part_number' => '191358-001',
                'part_name' => 'IC SUB; EBW; 15850; B; RH STT; BT1UG RCL; INBOARD SUB; BLANKED; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'part_number' => '191844-001',
                'part_name' => 'IC CKT; EBW; 15849; D; LH STT; BT1UG RCL; 2 CKTS ON ALU PLATE; QPFF; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'part_number' => '191845-001',
                'part_name' => 'IC SUB; EBW; 15849; C; LH STT; BT1UG RCL; OUTBOARD SUB; BLANKED; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'part_number' => '191852-001',
                'part_name' => 'IC CKT; EBW; 15850; C; RH STT; BT1UG RCL; 2 CKTS ON ALU PLATE; QPFF; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'part_number' => '191853-001',
                'part_name' => 'IC SUB; EBW; 15850; B; RH STT; BT1UG RCL;OUTBOARD SUB; BLANKED; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'part_number' => '192064-001',
                'part_name' => 'IC CKT; EBW; 15849; E; LH STT; BT1UG RCL; 2 CKTS ON ALU PLATE; CVLY; QPFF; PROTOTYPE; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'part_number' => '192065-001',
                'part_name' => 'IC CKT; EBW; 15850; E; RH STT; BT1UG RCL; 2 CKTS ON ALU PLATE; CVLY; QPFF; PROTOTYPE; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'part_number' => '192187-001',
                'part_name' => 'EBW;15849; LH STT;BT1UG;CVLY;2 CKTS ON ALU PLATE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'part_number' => '192188-001',
                'part_name' => 'EBW;15849;D;LH STT; BT1UG RCL; INBOARD SUB',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'part_number' => '192191-001',
                'part_name' => 'EBW;15849;D;LH STT; BT1UG RCL; OUTBOARD SUB',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'part_number' => '192194-001',
                'part_name' => 'EBW;15849; RH STT;BT1UG;CVLY;2 CKTS ON ALU PLATE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'part_number' => '192195-001',
                'part_name' => 'EBW;15850;D;RH STT; BT1UG RCL; INBOARD SUB',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'part_number' => '192197-001',
                'part_name' => 'EBW;15850;D;RH STT; BT1UG RCL; OUTBOARD SUB',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'part_number' => '173238-001',
                'part_name' => 'Flex AU Left',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'part_number' => '173263-001',
                'part_name' => 'Flex AU Right',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'part_number' => '175530-001',
                'part_name' => 'FLEXTRONIC- C218110 BLL INNER',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'part_number' => '175540-001',
                'part_name' => 'FLEXTRONIC- C218110 BLL OUTER',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'part_number' => '175608-001',
                'part_name' => 'FLEXTRONIC- C218 FL LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'part_number' => '175620-001',
                'part_name' => 'FLEXTRONIC- C218 FL RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'part_number' => '175841-001',
                'part_name' => 'FLEXTRONICS W221 DRL LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'part_number' => '175842-001',
                'part_name' => 'FLEXTRONICS W221 DRL RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'part_number' => '175904-001',
            'part_name' => 'Flextronic:MML W221 Left (175904-001)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'part_number' => '175905-001',
            'part_name' => 'Flextronic:MML W221 Right (175905-001)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'part_number' => '177095-001',
                'part_name' => 'FLEXTRONICS C218 CL LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'part_number' => '177096-001',
                'part_name' => 'FLEXTRONICS C218 CL RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'part_number' => '178424-001',
                'part_name' => 'FLEXTRONICS PASSAT REV.A',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'part_number' => '179123-001',
                'part_name' => 'FLEXTRONICS DODGE RH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'part_number' => '179140-001',
                'part_name' => 'FLEXTRONICS DODGE LH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'part_number' => '179641-001',
                'part_name' => 'FLEXTRONICS R8 FL LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'part_number' => '179642-001',
                'part_name' => 'FLEXTRONICS R8 FL RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'part_number' => '179653-001',
                'part_name' => 'FLEXTRONICS R8 GL LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'part_number' => '179661-001',
                'part_name' => 'FLEXTRONICS R8 GL RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'part_number' => '179671-001',
                'part_name' => 'FLEXTRONICS R8 SPOT RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'part_number' => '179686-001',
                'part_name' => 'FLEXTRONICS R8 SPOT LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'part_number' => '179693-001',
                'part_name' => 'FLEXTRONICS R8 CL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'part_number' => '179935-001',
                'part_name' => 'FLEXTRONICS R8 BLL/ TFL LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'part_number' => '180491-001',
                'part_name' => 'FLEXTRONICS;GM A1LL HL LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'part_number' => '180492-001',
                'part_name' => 'FLEXTRONICS;GM A1LL HL RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'part_number' => '180493-001',
                'part_name' => 'FLEXTRONICS;GM A1LL AUX LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'part_number' => '180494-001',
                'part_name' => 'FLEXTRONICS;GM A1LL AUX RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 61,
                'part_number' => '181109-001',
                'part_name' => 'FLEXTRONICS R8 BLL/ TFL RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 62,
                'part_number' => '181337-001',
                'part_name' => 'FLEXTRONICS; CHRYSLER LWR;AAI',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 63,
                'part_number' => '182590-001',
                'part_name' => 'Flextronics; AAI1H-38202078; rev001MP; Ford U375; Double-Sided; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 64,
                'part_number' => '182594-001',
                'part_name' => 'Flextronics; AAI1H-38017849; rev 003AMP; Chrysler LWR; Double-Sided',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 65,
                'part_number' => '183350-001',
                'part_name' => 'Flextronics; VENTRA U502 REAR FOG NEW REV.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 66,
                'part_number' => '183381-001',
                'part_name' => 'FLEXTRONICS MAPLIGHT SENSOR LH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 67,
                'part_number' => '183386-001',
                'part_name' => 'FLEXTRONICS MAPLIGHT SENSOR RH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 68,
                'part_number' => '184450-001',
                'part_name' => 'FLEXTRONICS FMC7-GR2B-F519A58-CY-PIA-49-RH; D544 SENSOR RH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 69,
                'part_number' => '184452-001',
                'part_name' => 'FLEXTRONICS FMC7-GR2B-F519A58-CY-PIA-50-LH; D544 SENSOR LH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 70,
                'part_number' => '184701-001',
                'part_name' => 'FLEXTRONICS FORD LOA',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 71,
                'part_number' => '184836-001',
                'part_name' => 'FLEXTRONICS PASSAT REV.C',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 72,
                'part_number' => '186373-001',
                'part_name' => 'FLEXTRONICS; PSA A88',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 73,
                'part_number' => '186374-001',
                'part_name' => 'FLEXTRONICS; REV 003; PSA BMPV',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 74,
                'part_number' => '191097-001',
                'part_name' => 'Flextronics; AAI1H-38203608; rev 001; Ford U375; Double-Sided; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 75,
                'part_number' => '191667-001',
                'part_name' => 'CKT;FLEX;rev.B;VOLVO RLL;DOUBLE-SIDED;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 76,
                'part_number' => '182811-001',
            'part_name' => 'CKT; Flex; AAI1H-38203608-001; PCB (MENTOR) UP375; Double-Sided; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 77,
                'part_number' => '181110-001',
                'part_name' => 'FLEXTRONICS R8 BLL/ TFL LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 78,
                'part_number' => '175671-001',
                'part_name' => 'Flextronic Ford Reps',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 79,
                'part_number' => '176303-001',
                'part_name' => 'Flextronic Fiat 846 ABS NEW rev.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 80,
                'part_number' => '178703-001',
                'part_name' => 'FLEXTRONICS; CHRYSLER',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 81,
                'part_number' => '179709-001',
                'part_name' => 'Flextronic 30 Degree new rev.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 82,
                'part_number' => '180331-001',
                'part_name' => 'Flextronics K2XX DO new rev',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 83,
                'part_number' => '180583-001',
                'part_name' => 'FLEXTRONICS CEPS ABS',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 84,
                'part_number' => '180588-001',
                'part_name' => 'FLEXTRONICS CEPS TO',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 85,
                'part_number' => '180727-001',
                'part_name' => 'FLEXTRONICS FORD S550',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 86,
                'part_number' => '181637-001',
                'part_name' => 'FLEXTRONICS PEUGEOT ABS NEW REV',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 87,
                'part_number' => '181638-001',
                'part_name' => 'FLEXTRONICS PEUGEOT TRQ NEW REV',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 88,
                'part_number' => '186392-001',
            'part_name' => 'FLEX -WZ; BMW SPEPS (NEW)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 89,
                'part_number' => '186440-001',
            'part_name' => 'FLEX -TIMI; BMW SPEPS (NEW)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 90,
                'part_number' => '188717-001',
                'part_name' => 'Flextronic 30 Degree new rev.B',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id' => 91,
                'part_number' => '189469-001',
                'part_name' => 'FLEX SARVAR TOUCHSLIDER',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id' => 92,
                'part_number' => '186720-001',
                'part_name' => 'TE CONNECTIVITY; FOCUS PLUS',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id' => 93,
                'part_number' => '186427-001',
                'part_name' => 'GEORGE SCHMITT & CO; HONEYWELL RFID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id' => 94,
                'part_number' => '178462-001',
                'part_name' => 'HELLA AUDI A3 INTERIOR',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id' => 95,
                'part_number' => '180742-001',
                'part_name' => 'HELLA INSIGNIA RIGHT NEW PARTNUMBER',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id' => 96,
                'part_number' => '180743-001',
                'part_name' => 'HELLA INSIGNIA LEFT NEW PARTNUMBER',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            96 => 
            array (
                'id' => 97,
                'part_number' => '178210-001',
            'part_name' => 'John-Elec RH SHARAN (178210)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            97 => 
            array (
                'id' => 98,
                'part_number' => '178212-001',
            'part_name' => 'John-Elec LH SHARAN (178212)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            98 => 
            array (
                'id' => 99,
                'part_number' => '178214-001',
            'part_name' => 'John-Elec RH SHARAN (178214)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            99 => 
            array (
                'id' => 100,
                'part_number' => '178216-001',
            'part_name' => 'John-Elec LH SHARAN (178216)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            100 => 
            array (
                'id' => 101,
                'part_number' => '178228-001',
            'part_name' => 'John-Elec RH SHARAN (178228)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            101 => 
            array (
                'id' => 102,
                'part_number' => '178230-001',
            'part_name' => 'John-Elec LH SHARAN (178230)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            102 => 
            array (
                'id' => 103,
                'part_number' => '178232-001',
            'part_name' => 'John-Elec RH SHARAN (178232)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            103 => 
            array (
                'id' => 104,
                'part_number' => '178234-001',
            'part_name' => 'John-Elec LH SHARAN (178234)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            104 => 
            array (
                'id' => 105,
                'part_number' => '178236-001',
            'part_name' => 'John-Elec RH SHARAN (178236)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            105 => 
            array (
                'id' => 106,
                'part_number' => '189266-001',
                'part_name' => 'KATECHO;RM3341;TWITCHVIEW LARGE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            106 => 
            array (
                'id' => 107,
                'part_number' => '192237-001',
                'part_name' => 'FINGOOD; KATECHO; RM3286; TWITCHVIEW; SMALL ELECTRODE; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            107 => 
            array (
                'id' => 108,
                'part_number' => '190262-001',
                'part_name' => 'KATECHO;RM3397;NEUROMETRIX;QUELL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            108 => 
            array (
                'id' => 109,
                'part_number' => '182336-001',
                'part_name' => 'HAITEC TO',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            109 => 
            array (
                'id' => 110,
                'part_number' => '184592-001',
                'part_name' => 'KIMBALL CI XX new partno',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            110 => 
            array (
                'id' => 111,
                'part_number' => '184605-001',
                'part_name' => 'KIMBALL 9BXX',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            111 => 
            array (
                'id' => 112,
                'part_number' => '188152-001',
                'part_name' => 'KIMBALL 9BUX;REV 003;DOUBLE SIDED',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            112 => 
            array (
                'id' => 113,
                'part_number' => '190221-001',
                'part_name' => 'KIMBALL 9BUX;REV 004;DOUBLE SIDED',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            113 => 
            array (
                'id' => 114,
                'part_number' => '181060-001',
            'part_name' => 'KUESTER (RENAULT- LFC)new rev',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            114 => 
            array (
                'id' => 115,
                'part_number' => '181061-001',
            'part_name' => 'KUESTER (FORD-LFC) new rev',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            115 => 
            array (
                'id' => 116,
                'part_number' => '181062-001',
            'part_name' => 'KUESTER(NEW BMW LFC) new rev',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            116 => 
            array (
                'id' => 117,
                'part_number' => '186823-001',
                'part_name' => 'Flextronics MAPLAMP SHORT NEW',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            117 => 
            array (
                'id' => 118,
                'part_number' => '186824-001',
                'part_name' => 'Flextronics MAPLAMP LONG NEW',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            118 => 
            array (
                'id' => 119,
                'part_number' => '189828-001',
                'part_name' => 'MCLAREN; LEFT ARTICULATION',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            119 => 
            array (
                'id' => 120,
                'part_number' => '189853-001',
                'part_name' => 'MCLAREN; RIGHT ARTICULATION',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            120 => 
            array (
                'id' => 121,
                'part_number' => '188138-001',
                'part_name' => 'MEDICOMP;SINGLE CHANNEL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            121 => 
            array (
                'id' => 122,
                'part_number' => '188997-001',
                'part_name' => 'MEDICOMP DOUBLE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            122 => 
            array (
                'id' => 123,
                'part_number' => '192826-001',
                'part_name' => 'METHODE; 10356222-01; ITO MAPLIGHT; ITO',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            123 => 
            array (
                'id' => 124,
                'part_number' => '185669-001',
                'part_name' => 'MULU NEW',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            124 => 
            array (
                'id' => 125,
                'part_number' => '186835-001',
                'part_name' => 'MULU  NEW COPPER LAMINATE BUILD',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            125 => 
            array (
                'id' => 126,
                'part_number' => '191181-001',
                'part_name' => 'NANOTHINGS',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            126 => 
            array (
                'id' => 127,
                'part_number' => '191270-001',
                'part_name' => 'CKT; NANOTHINGS; ACSIP T US',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            127 => 
            array (
                'id' => 128,
                'part_number' => '191271-001',
                'part_name' => 'CKT; NANOTHINGS; ACSIP T EU',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            128 => 
            array (
                'id' => 129,
                'part_number' => '191272-001',
                'part_name' => 'CKT; NANOTHINGS; ACSIP T AU',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            129 => 
            array (
                'id' => 130,
                'part_number' => '191978-001',
                'part_name' => 'CKT; NANOTHINGS; ACSIP T US; NEW CU DESIGN',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            130 => 
            array (
                'id' => 131,
                'part_number' => '191979-001',
                'part_name' => 'CKT; NANOTHINGS; ACSIP T EU',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            131 => 
            array (
                'id' => 132,
                'part_number' => '186123-001',
                'part_name' => 'NYXOAH; COIL PATCH',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            132 => 
            array (
                'id' => 133,
                'part_number' => '184924-001',
                'part_name' => 'PREH 13052-041',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            133 => 
            array (
                'id' => 134,
                'part_number' => '186365-001',
                'part_name' => 'PREH 13052-095/0001',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            134 => 
            array (
                'id' => 135,
                'part_number' => '187596-001',
                'part_name' => 'PREH 13052-042 REV 0007',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            135 => 
            array (
                'id' => 136,
                'part_number' => '187601-001',
                'part_name' => 'PREH 13052-044 REV 0007',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            136 => 
            array (
                'id' => 137,
                'part_number' => '187603-001',
                'part_name' => 'PREH 13052-053 REV 0007',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            137 => 
            array (
                'id' => 138,
                'part_number' => '188063-001',
                'part_name' => 'PREH 13052-087/0005 NEW PANEL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            138 => 
            array (
                'id' => 139,
                'part_number' => '188159-001',
                'part_name' => 'PREH 13052-043 REV 0008',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            139 => 
            array (
                'id' => 140,
                'part_number' => '188784-001',
                'part_name' => 'PREH 13052-033/0301',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            140 => 
            array (
                'id' => 141,
                'part_number' => '188806-001',
                'part_name' => 'PREH 13052-034/003',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            141 => 
            array (
                'id' => 142,
                'part_number' => '190104-001',
                'part_name' => 'PREH 13052-037/0005; zif change',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            142 => 
            array (
                'id' => 143,
                'part_number' => '190110-001',
                'part_name' => 'PREH 13052-038/0005; zif change',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            143 => 
            array (
                'id' => 144,
                'part_number' => '190116-001',
                'part_name' => 'PREH 13052-055/0005; zif change',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            144 => 
            array (
                'id' => 145,
                'part_number' => '190122-001',
                'part_name' => 'PREH 13052-056/0005; zif change',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            145 => 
            array (
                'id' => 146,
                'part_number' => '191152-001',
                'part_name' => 'PREH 13052-196',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            146 => 
            array (
                'id' => 147,
                'part_number' => '191153-001',
                'part_name' => 'PREH 13052-197',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            147 => 
            array (
                'id' => 148,
                'part_number' => '191155-001',
                'part_name' => 'PREH; 13052-034/0005',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            148 => 
            array (
                'id' => 149,
                'part_number' => '191684-001',
                'part_name' => 'CKT;PREH;13052-029/0008;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            149 => 
            array (
                'id' => 150,
                'part_number' => '191694-001',
                'part_name' => 'CKT;PREH;13052-030/0008;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            150 => 
            array (
                'id' => 151,
                'part_number' => '191701-001',
                'part_name' => 'CKT;PREH;13052-031/0007;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            151 => 
            array (
                'id' => 152,
                'part_number' => '191711-001',
                'part_name' => 'CKT;PREH;13052-032/0007;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            152 => 
            array (
                'id' => 153,
                'part_number' => '191774-001',
                'part_name' => 'PREH 13052-114/0007; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            153 => 
            array (
                'id' => 154,
                'part_number' => '191775-001',
                'part_name' => 'CKT;PREH;13052-115/0007;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            154 => 
            array (
                'id' => 155,
                'part_number' => '191776-001',
                'part_name' => 'CKT;PREH;13052-116/0007;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            155 => 
            array (
                'id' => 156,
                'part_number' => '191777-001',
                'part_name' => 'CKT; PREH; 13052-117/0007;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            156 => 
            array (
                'id' => 157,
                'part_number' => '191932-001',
                'part_name' => 'PREH 13052-170 / 0005',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            157 => 
            array (
                'id' => 158,
                'part_number' => '191943-001',
                'part_name' => 'PREH 13052-238/0003',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            158 => 
            array (
                'id' => 159,
                'part_number' => '192089-001',
                'part_name' => 'PREH 13052-0192/0002',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            159 => 
            array (
                'id' => 160,
                'part_number' => '192090-001',
                'part_name' => 'PREH 13052-0193/0003',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            160 => 
            array (
                'id' => 161,
                'part_number' => '192091-001',
                'part_name' => 'PREH 13052-0194/0002',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            161 => 
            array (
                'id' => 162,
                'part_number' => '192092-001',
                'part_name' => 'PREH 13052-0195/0003',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            162 => 
            array (
                'id' => 163,
                'part_number' => '192169-001',
                'part_name' => 'PREH 13052-033/0501 INDEX 7;RZI; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            163 => 
            array (
                'id' => 164,
                'part_number' => '192170-001',
                'part_name' => 'PREH; 13052-034/0005 INDEX 6',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            164 => 
            array (
                'id' => 165,
                'part_number' => '183550-001',
                'part_name' => 'PREH GMBH 13052-023/0400',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            165 => 
            array (
                'id' => 166,
                'part_number' => '192387-001',
                'part_name' => 'PRONAT ; MERCURY ; 675-1768-04',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            166 => 
            array (
                'id' => 167,
                'part_number' => '170559-001',
                'part_name' => 'TRW 559',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            167 => 
            array (
                'id' => 168,
                'part_number' => '170561-001',
                'part_name' => 'TRW 561',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            168 => 
            array (
                'id' => 169,
                'part_number' => '183545-001',
                'part_name' => 'TRW 545',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            169 => 
            array (
                'id' => 170,
                'part_number' => '191433-001',
                'part_name' => 'IC CKT;TYCO;REV.D;FULL ASSEMBLY AT NTPI',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            170 => 
            array (
                'id' => 171,
                'part_number' => '181512-001',
                'part_name' => 'TYCO New rev',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            171 => 
            array (
                'id' => 172,
                'part_number' => '148564-501',
            'part_name' => 'UNI; PINK; WCU; T188001;0.75IN(19.05MM);100ME;45R; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            172 => 
            array (
                'id' => 173,
                'part_number' => '148565-501',
            'part_name' => 'UNI; PINK; WCU; T188002;0.75IN(19.05MM); 100ME; 55R',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            173 => 
            array (
                'id' => 174,
                'part_number' => '148565-505',
            'part_name' => 'UNI;PEARL;SUW;T907104;0.63IN(16MM);100ME;75R;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            174 => 
            array (
                'id' => 175,
                'part_number' => '148566-501',
            'part_name' => 'UNI PINK;WCU;T188003;0.75IN (19.05MM); 100ME;67R,SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            175 => 
            array (
                'id' => 176,
                'part_number' => '148566-502',
            'part_name' => 'UNI;PINK;WCU;T188003;1.0IN(25.4MM);100ME;67R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            176 => 
            array (
                'id' => 177,
                'part_number' => '148566-509',
            'part_name' => 'UNI PINK;0.39IN(10MM);100ME;67R',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            177 => 
            array (
                'id' => 178,
                'part_number' => '148566-511',
            'part_name' => 'UNI; PINK; WCU; T188003; 0.472IN(12MM); 100ME; 67R; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            178 => 
            array (
                'id' => 179,
                'part_number' => '148566-521',
            'part_name' => 'UNI;PINK;WCU;T188003;0.75IN(19.05MM);200ME;67R SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            179 => 
            array (
                'id' => 180,
                'part_number' => '148566-525',
            'part_name' => 'UNI;PINK; WCU; T188003;0.984IN(25MM); 200ME;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            180 => 
            array (
                'id' => 181,
                'part_number' => '148567-501',
            'part_name' => 'UNI;PINK;WCU;T188004;0.75IN(19.05MM);100ME;75R; SPLICING;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            181 => 
            array (
                'id' => 182,
                'part_number' => '148567-504',
            'part_name' => 'UNI; PINK;WCU; T188004;0.394IN(10MM);100ME;75R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            182 => 
            array (
                'id' => 183,
                'part_number' => '148567-506',
            'part_name' => 'UNI; PINK; WCU; T188004; 1.0IN (25.4MM); 100ME;75R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            183 => 
            array (
                'id' => 184,
                'part_number' => '148567-507',
            'part_name' => 'UNI; PINK; WCU; T188004; 0.75IN (19.05MM); 100ME;75R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            184 => 
            array (
                'id' => 185,
                'part_number' => '148568-503',
            'part_name' => 'UNI PINK; WCU;T188007;0.75IN(19MM);100ME;80R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            185 => 
            array (
                'id' => 186,
                'part_number' => '148569-502',
            'part_name' => 'UNI;PINK;WCU;T188045;0.75IN(19.05MM);100ME;45;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            186 => 
            array (
                'id' => 187,
                'part_number' => '148569-506',
            'part_name' => 'UNI PINK; WCU T188045;1.0IN(25.4MM);100ME;45;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            187 => 
            array (
                'id' => 188,
                'part_number' => '148569-507',
            'part_name' => 'UNI;PINK;UNC;T188045;0.866IN(22MM);100ME;45;MOQ;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            188 => 
            array (
                'id' => 189,
                'part_number' => '148569-508',
            'part_name' => 'UNI;PINK;UNC;T188045;0.394IN(10MM);100ME;45;MOQ;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            189 => 
            array (
                'id' => 190,
                'part_number' => '148570-501',
            'part_name' => 'UNI;PINK;WCU;0.75IN(19.05MM);100ME;55;T188055;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            190 => 
            array (
                'id' => 191,
                'part_number' => '148570-511',
            'part_name' => 'UNI;PINK;WCU;T188055;1.0IN(25.4MM)100ME;55; SPLICING;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            191 => 
            array (
                'id' => 192,
                'part_number' => '148571-506',
            'part_name' => 'UNI;PINK;UNC;T188060;0.75IN(19MM);100ME;60;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            192 => 
            array (
                'id' => 193,
                'part_number' => '148572-501',
            'part_name' => 'UNI; PINK; WCU; 0.75IN (19.05MM); 100ME; 67; T188067; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            193 => 
            array (
                'id' => 194,
                'part_number' => '148572-502',
            'part_name' => 'UNI;PINK;WCU;T188067;1.0IN(25.4MM);100ME;67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            194 => 
            array (
                'id' => 195,
                'part_number' => '148572-504',
            'part_name' => 'UNI; PINK; WCU; 0.59IN (15MM); 100ME; 67; T188067; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            195 => 
            array (
                'id' => 196,
                'part_number' => '148572-506',
            'part_name' => 'UNI; PINK; WCU; 0.63IN (16MM); 100ME; 67; T188067; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            196 => 
            array (
                'id' => 197,
                'part_number' => '148572-507',
            'part_name' => 'UNI;PINK;WCU;T188067;0.3941IN(10MM);100ME;67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            197 => 
            array (
                'id' => 198,
                'part_number' => '148572-508',
            'part_name' => 'UNI;PINK;WCU;T188067; 0.75IN (19.05MM);200ME;67; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            198 => 
            array (
                'id' => 199,
                'part_number' => '148572-522',
            'part_name' => 'UNI;PINK;WCU;T188067;0.866IN(22MM);100ME;67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            199 => 
            array (
                'id' => 200,
                'part_number' => '148572-600',
            'part_name' => 'UNI PINK;0.75IN(19.05MM);20ME ;67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            200 => 
            array (
                'id' => 201,
                'part_number' => '148573-501',
            'part_name' => 'UNI PINK;WCU;T188070 0.75IN (19.05MM);100ME;70;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            201 => 
            array (
                'id' => 202,
                'part_number' => '148573-507',
            'part_name' => 'UNI;PINK;WCU;T188070;0.59IN(15MM);100ME;70;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            202 => 
            array (
                'id' => 203,
                'part_number' => '148573-508',
            'part_name' => 'UNI;PINK;WCU;T188070;1.0IN(25.4MM);100ME;70;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            203 => 
            array (
                'id' => 204,
                'part_number' => '148575-503',
            'part_name' => 'UNI;PINK;WCU;T188075;1.0IN(25.4MM);100ME;67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            204 => 
            array (
                'id' => 205,
                'part_number' => '148575-504',
            'part_name' => 'UNI;PINK;WCU;T188075;0.75IN(19.05MM)200ME;55; SPLICING;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            205 => 
            array (
                'id' => 206,
                'part_number' => '148575-505',
            'part_name' => 'UNI;PINK;WCU;T188075;0.75IN(19MM);100ME;75;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            206 => 
            array (
                'id' => 207,
                'part_number' => '148575-506',
            'part_name' => 'UNI;PINK; WCU; T188075;0.86IN(22MM);100ME; 75;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            207 => 
            array (
                'id' => 208,
                'part_number' => '148575-507',
            'part_name' => 'UNI;PINK;WCU;T188075;0.3941IN(10MM);100ME;75;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            208 => 
            array (
                'id' => 209,
                'part_number' => '148575-508',
            'part_name' => 'UNI;PINK;WCU;T188075;0.631IN(10MM);100ME;75;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            209 => 
            array (
                'id' => 210,
                'part_number' => '148575-510',
            'part_name' => 'UNI PINK;WCU;T188075;0.867IN(22MM);100ME;75;S;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            210 => 
            array (
                'id' => 211,
                'part_number' => '148575-511',
            'part_name' => 'UNI;PINK;UNC;T188075;0.591IN(15MM);100ME;75;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            211 => 
            array (
                'id' => 212,
                'part_number' => '148575-528',
            'part_name' => 'UNI PINK;WCU;T188075;0.63IN(16MM);200ME;75;S;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            212 => 
            array (
                'id' => 213,
                'part_number' => '148576-501',
            'part_name' => 'UNI;PINK;WCU;0.75IN(19.05MM);100ME;55;T188055;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            213 => 
            array (
                'id' => 214,
                'part_number' => '148576-504',
            'part_name' => 'UNI PINK;WCU;T188080; 0.75IN(19.05MM);200ME;80;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            214 => 
            array (
                'id' => 215,
                'part_number' => '148576-513',
            'part_name' => 'UNI PINK;UNC;T188080;1.0IN(25.4MM);100ME; 80;CTO; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            215 => 
            array (
                'id' => 216,
                'part_number' => '148577-503',
            'part_name' => 'UNI;PINK;WCU;T188090; 0.75IN (19.05MM);100ME;90; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            216 => 
            array (
                'id' => 217,
                'part_number' => '148579-501',
            'part_name' => 'UNI;WHITE;SUW;T188201;0.75IN(19.05MM);100ME;45R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            217 => 
            array (
                'id' => 218,
                'part_number' => '148579-502',
            'part_name' => 'UNI;WHITE;UNC;T188201;1.0IN(25.4MM);100ME;45R;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            218 => 
            array (
                'id' => 219,
                'part_number' => '148580-501',
            'part_name' => 'UNI;WHITE; SUW;T188202;0.75IN(19.05MM);100ME;55R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            219 => 
            array (
                'id' => 220,
                'part_number' => '148580-502',
            'part_name' => 'UNI;WHITE; SUW;T188202;1.0IN(25.4MM);100ME;55;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            220 => 
            array (
                'id' => 221,
                'part_number' => '148580-507',
            'part_name' => 'UNI;WHITE;UNC;T188202;0.59IN(15MM);100ME;55R;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            221 => 
            array (
                'id' => 222,
                'part_number' => '148581-501',
            'part_name' => 'UNI;WHITE;SUW; T188203;0.75IN (19.05MM); 100ME; 67R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            222 => 
            array (
                'id' => 223,
                'part_number' => '148581-503',
            'part_name' => 'UNI;WHITE; SUW; T188203;0.75IN(19.05MM);200ME; 67R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            223 => 
            array (
                'id' => 224,
                'part_number' => '148581-508',
            'part_name' => 'UNI;WHITE;SUW; T188203;1.0IN (25.4MM); 100ME; 67R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            224 => 
            array (
                'id' => 225,
                'part_number' => '148581-512',
            'part_name' => 'UNI;WHITE;UNC;T188203;0.47IN(12MM);100M;67R;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            225 => 
            array (
                'id' => 226,
                'part_number' => '148581-550',
            'part_name' => 'UNI;WHITE;UNC;T188203;0.984IN(25MM);200ME;67R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            226 => 
            array (
                'id' => 227,
                'part_number' => '148582-501',
            'part_name' => 'UNI;WHITE;SUW;T188204;0.75IN(19MM);100ME;75R; SPLICING;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            227 => 
            array (
                'id' => 228,
                'part_number' => '148582-502',
            'part_name' => 'UNI; WHITE; SUW; T188204;1.0IN(25.4MM);100ME; 75R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            228 => 
            array (
                'id' => 229,
                'part_number' => '148582-507',
            'part_name' => 'UNI; WHITE; SUW; T188204; 0.75IN(19.05MM);200ME;75R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            229 => 
            array (
                'id' => 230,
                'part_number' => '148582-513',
            'part_name' => 'UNI; WHITE; SUW; SUW;T1882040;0.63IN (16MM);100ME;75R',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            230 => 
            array (
                'id' => 231,
                'part_number' => '148584-501',
            'part_name' => 'UNI;WHITE;SUW;T188245;0.75N;(19.05MM);100ME;45;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            231 => 
            array (
                'id' => 232,
                'part_number' => '148584-507',
            'part_name' => 'UNI;WHITE;SUW;T188245;1.0N;(25.4MM);100ME;45;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            232 => 
            array (
                'id' => 233,
                'part_number' => '148585-501',
            'part_name' => 'UNI; Production; WHITE; SUW; 188255; 0.75IN(19.05MM);100ME;55;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            233 => 
            array (
                'id' => 234,
                'part_number' => '148585-502',
            'part_name' => 'UNI;WHITE;SUW;T188255;1.0IN(25.4MM)100ME;55;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            234 => 
            array (
                'id' => 235,
                'part_number' => '148586-502',
            'part_name' => 'UNI;WHITE;UNC;T188260;0.75IN(19MM);100ME;60;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            235 => 
            array (
                'id' => 236,
                'part_number' => '148587-501',
            'part_name' => 'UNI; WHITE; SUW; T188267; 0.75IN(19.05MM); 100ME; 67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            236 => 
            array (
                'id' => 237,
                'part_number' => '148587-502',
            'part_name' => 'UNI;WHITE;SUW; T188267;1.0IN (25.4MM); 100ME; 67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            237 => 
            array (
                'id' => 238,
                'part_number' => '148587-509',
            'part_name' => 'UNI; WHITE;SUW;0.75IN (19.05MM);200ME; 67; T188267;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            238 => 
            array (
                'id' => 239,
                'part_number' => '148587-510',
                'part_name' => 'UNI WHITE 67',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            239 => 
            array (
                'id' => 240,
                'part_number' => '148587-511',
            'part_name' => 'UNI;WHITE;SUW;T188267;0.63IN(16MM);100ME;67;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            240 => 
            array (
                'id' => 241,
                'part_number' => '148587-600',
            'part_name' => 'UNI WHITE;0.75IN(19.05MM);20ME ;67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            241 => 
            array (
                'id' => 242,
                'part_number' => '148588-501',
            'part_name' => 'UNI;WHITE;SUW;T188270;0.75IN(19.05MM);100ME;70;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            242 => 
            array (
                'id' => 243,
                'part_number' => '148588-502',
            'part_name' => 'UNI; WHITE;SUW;T188270;1.0IN(25.4MM);100ME;70;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            243 => 
            array (
                'id' => 244,
                'part_number' => '148588-504',
            'part_name' => 'UNI;WHITE;UNC;T188270;0.984IN(25MM);100ME;70;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            244 => 
            array (
                'id' => 245,
                'part_number' => '148588-506',
            'part_name' => 'UNI;WHITE;UNC;T188270;0.591IN(15MM);100M;70;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            245 => 
            array (
                'id' => 246,
                'part_number' => '148588-507',
            'part_name' => 'UNI;WHITE;UNC;T188570;0.512IN(13MM);100M;70;CTO;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            246 => 
            array (
                'id' => 247,
                'part_number' => '148588-511',
            'part_name' => 'UNI WHITE; UNC T188270;0.75IN(19MM);200M;70;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            247 => 
            array (
                'id' => 248,
                'part_number' => '148590-502',
            'part_name' => 'UNI; WHITE; SUW; T188275; 0.867IN (22MM); 100ME; 75;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            248 => 
            array (
                'id' => 249,
                'part_number' => '148590-504',
            'part_name' => 'UNI;WHITE;SUW;T188275;0.75IN(19.05MM);200ME;75;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            249 => 
            array (
                'id' => 250,
                'part_number' => '148590-506',
            'part_name' => 'UNI; WHITE; SUW; T188275;0.63IN (16MM); 100ME; 75;  SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            250 => 
            array (
                'id' => 251,
                'part_number' => '148590-507',
            'part_name' => 'UNI;WHITE;SUW;T188275;0.75IN(19MM);100ME;75;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            251 => 
            array (
                'id' => 252,
                'part_number' => '148590-508',
            'part_name' => 'UNI;WHITE;SUW;T188275;SUW;1.0IN;(25.4MM);100ME;75;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            252 => 
            array (
                'id' => 253,
                'part_number' => '148590-509',
            'part_name' => 'UNI; WHITE; SUW; T188275;0.394IN (10MM); 100ME; 75;  SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            253 => 
            array (
                'id' => 254,
                'part_number' => '148590-510',
            'part_name' => 'UNI;WHITE;UNC;T188275;0.59IN(15MM);100ME;75;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            254 => 
            array (
                'id' => 255,
                'part_number' => '148590-511',
            'part_name' => 'UNI; WHITE; SUW; SUW; T188275;0.827IN (21MM);100ME;75;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            255 => 
            array (
                'id' => 256,
                'part_number' => '148590-526',
            'part_name' => 'UNI;WHITE;UNC;T188275;0.63IN(16MM);200ME;75 SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            256 => 
            array (
                'id' => 257,
                'part_number' => '148591-509',
            'part_name' => 'UNI;WHITE;SUW;T188280;0.63IN(16MM);100ME;80; SPLICING;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            257 => 
            array (
                'id' => 258,
                'part_number' => '148592-501',
            'part_name' => 'UNI; WHITE; SUW; T188290; 0.75IN (19.05MM); 100ME; 90; SPLICING TAP',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            258 => 
            array (
                'id' => 259,
                'part_number' => '148592-502',
            'part_name' => 'UNI;WHITE;SUW;T188290;1.00IN(25.4MM);100ME;90',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            259 => 
            array (
                'id' => 260,
                'part_number' => '148592-507',
            'part_name' => 'UNI;WHITE;SUW;T188290;0.630IN(16MM);100ME;90;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            260 => 
            array (
                'id' => 261,
                'part_number' => '148592-522',
            'part_name' => 'UNI; WHITE; SUW; T188290;0.75IN(19.05MM);200ME;90',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            261 => 
            array (
                'id' => 262,
                'part_number' => '148598-502',
            'part_name' => 'UNI;BLUE;SUW;T188407;0.75IN(19MM);100ME;80R;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            262 => 
            array (
                'id' => 263,
                'part_number' => '148675-501',
            'part_name' => 'UNI YELLOW;UNC;T193003;0.75IN(19.05MM);100ME;67R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            263 => 
            array (
                'id' => 264,
                'part_number' => '148676-501',
            'part_name' => 'UNI;YELLOW;UNC;T193004;0.75IN(19MM);100ME;75R;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            264 => 
            array (
                'id' => 265,
                'part_number' => '148678-501',
            'part_name' => 'UNI;YELLOW;UNC;T193045;0.75IN(19.05MM);100ME;45;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            265 => 
            array (
                'id' => 266,
                'part_number' => '148678-503',
            'part_name' => 'UNI YELLOW; UNC; T193045; 0.433IN (11MM);100ME;45;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            266 => 
            array (
                'id' => 267,
                'part_number' => '148678-504',
            'part_name' => 'UNI;YELLOW;UNC;T193045;0.866IN(22MM);100ME;45;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            267 => 
            array (
                'id' => 268,
                'part_number' => '148678-505',
            'part_name' => 'UNI;YELLOW;UNC;T193045;0.394IN(10MM);100ME;45;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            268 => 
            array (
                'id' => 269,
                'part_number' => '148679-501',
                'part_name' => 'UNI YELLOW;100ME;55;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            269 => 
            array (
                'id' => 270,
                'part_number' => '148681-501',
            'part_name' => 'UNI YELLOW; UNC; T193067;0.75IN(19.05MM);100ME;67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            270 => 
            array (
                'id' => 271,
                'part_number' => '148681-506',
            'part_name' => 'UNI;YEL;UNC;T193067;0.394IN(10MM);100M;67;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            271 => 
            array (
                'id' => 272,
                'part_number' => '148682-501',
            'part_name' => 'UNI YELLOW;UNC;T193075;0.75IN;(19.05MM);100ME;75;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            272 => 
            array (
                'id' => 273,
                'part_number' => '148682-505',
            'part_name' => 'UNI;YELLOW;UNC;T193075;0.867IN(22MM);200ME;75;CTO;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            273 => 
            array (
                'id' => 274,
                'part_number' => '148682-507',
            'part_name' => 'UNI YELLOW; UNC; T193075; 0.867IN (22MM);100ME;75;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            274 => 
            array (
                'id' => 275,
                'part_number' => '148682-508',
            'part_name' => 'UNI;YELLOW;UNC;T193075;0.63IN(16MM);100ME;75;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            275 => 
            array (
                'id' => 276,
                'part_number' => '148682-528',
            'part_name' => 'UNI;  YELLOW; UNC;T193075;0.63IN(16MM);200ME;75ESPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            276 => 
            array (
                'id' => 277,
                'part_number' => '149283-502',
            'part_name' => 'UNI ORANGE;0.75IN(19MM);100ME;67',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            277 => 
            array (
                'id' => 278,
                'part_number' => '158549-502',
            'part_name' => 'UNI;GOLD;SUW;T188380;0.75IN(19MM);100M;80;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            278 => 
            array (
                'id' => 279,
                'part_number' => '158551-502',
            'part_name' => 'UNI;GOLD;SUW;T188367;0.75IN(19MM);100ME;67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            279 => 
            array (
                'id' => 280,
                'part_number' => '159485-501',
            'part_name' => 'UNI;GOLD;SUW;T188345;1.0IN(25MM);100M;45;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            280 => 
            array (
                'id' => 281,
                'part_number' => '162806-502',
            'part_name' => 'UNI;WHITE;SUW;T188205;0.75IN (19.05MM);100ME;60R; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            281 => 
            array (
                'id' => 282,
                'part_number' => '162936-502',
            'part_name' => 'UNI; FABRE; UNC; T187867; 0.75IN(19.05MM); 100ME; 67; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            282 => 
            array (
                'id' => 283,
                'part_number' => '162936-503',
            'part_name' => 'UNI; FABRE; UNC; T187867; 1.0IN(25.4MM); 100ME; 67; SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            283 => 
            array (
                'id' => 284,
                'part_number' => '162936-504',
            'part_name' => 'UNI;FABRE;UNC;T187867;0.984IN(25MM);100ME;67;SPLICING TAPE;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            284 => 
            array (
                'id' => 285,
                'part_number' => '162936-505',
            'part_name' => 'UNI;FABRE;UNC;T187867 0.5IN(13MM);100ME;67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            285 => 
            array (
                'id' => 286,
                'part_number' => '162936-506',
            'part_name' => 'UNI;FABRE;UNC;T187867 1.5IN(38.1MM);100ME;67;SPLICING TAPE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            286 => 
            array (
                'id' => 287,
                'part_number' => '162936-507',
                'part_name' => 'UNI;FABRE;UNC;T187867;0.866IN(22MM;100ME;67;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                287 => 
                array (
                    'id' => 288,
                    'part_number' => '162936-522',
                'part_name' => 'UNI;FABRE;UNC;T187867 0.75IN(19.05MM);200ME;67;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                288 => 
                array (
                    'id' => 289,
                    'part_number' => '162936-550',
                'part_name' => 'UNI;FABRE;UNC;T187867;350MM(13.78IN);100M;67;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                289 => 
                array (
                    'id' => 290,
                    'part_number' => '163034-502',
                'part_name' => 'UNI;FABRE;UNC;T187845;0.75IN(19.05MM);100ME;45;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                290 => 
                array (
                    'id' => 291,
                    'part_number' => '163034-505',
                'part_name' => 'UNI; FABRE; UNC; T187845; 0.866IN (22MM); 100ME; 45;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                291 => 
                array (
                    'id' => 292,
                    'part_number' => '163034-508',
                'part_name' => 'UNI;FABRE; UNC; ;T1878450;;0.63IN(16MM);100ME;45;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                292 => 
                array (
                    'id' => 293,
                    'part_number' => '163075-502',
                'part_name' => 'UNI;FABRE;UNC;T187875;0.75IN(19MM);100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                293 => 
                array (
                    'id' => 294,
                    'part_number' => '163075-503',
                'part_name' => 'UNI;FABRE; UNC;T187875;1.0IN(25.4MM);100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                294 => 
                array (
                    'id' => 295,
                    'part_number' => '163075-504',
                'part_name' => 'UNI; FABRE; SUW; T187875; 0.866IN (22MM); 100ME; 75; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                295 => 
                array (
                    'id' => 296,
                    'part_number' => '163075-512',
                'part_name' => 'UNI;FABRE;UNC;T187875;0.75IN (19MM);100ME;75; PROTO;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                296 => 
                array (
                    'id' => 297,
                    'part_number' => '163075-522',
                'part_name' => 'UNI;FABRE;UNC;T187875;0.75IN;(19MM);200ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                297 => 
                array (
                    'id' => 298,
                    'part_number' => '163087-502',
                'part_name' => 'UNI FABRE; UNC;T187880;0.75IN (19MM); 100ME;80 ;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                298 => 
                array (
                    'id' => 299,
                    'part_number' => '163087-503',
                'part_name' => 'UNI; FABRE; UNC; T187880; 1.0IN(25.4MM); 100ME;80; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                299 => 
                array (
                    'id' => 300,
                    'part_number' => '163087-506',
                'part_name' => 'UNI; FABRE; UNC;T187880;1.5IN (38.1 MM) 100ME; 80;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                300 => 
                array (
                    'id' => 301,
                    'part_number' => '163091-502',
                'part_name' => 'UNI;FABRE;UNC;T187890;0.75IN(19.05MM);100ME;90;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                301 => 
                array (
                    'id' => 302,
                    'part_number' => '163091-503',
                'part_name' => 'UNI;FABRE;UNC;T187890;1.0IN(25.4MM);100ME;90;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                302 => 
                array (
                    'id' => 303,
                    'part_number' => '163091-506',
                'part_name' => 'UNI;FABRE;UNC;T187890;1.50IN(38MM);100ME;90;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                303 => 
                array (
                    'id' => 304,
                    'part_number' => '165921-502',
                'part_name' => 'UNI; FABRE; UNC; T187855; 0.75IN(19.05MM); 100ME;55; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                304 => 
                array (
                    'id' => 305,
                    'part_number' => '165921-503',
                'part_name' => 'UNI; FABRE; UNC; T187855; 1.0IN(25.4MM); 100ME;55; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                305 => 
                array (
                    'id' => 306,
                    'part_number' => '169958-502',
                'part_name' => 'UNI FABRE; UNC;T187803;0.75IN (19.05MM); 100ME;67R ;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                306 => 
                array (
                    'id' => 307,
                    'part_number' => '169958-504',
                'part_name' => 'UNI;FABRE;UNC;1.0IN(25.4MM);100ME;67R;T187803;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                307 => 
                array (
                    'id' => 308,
                    'part_number' => '169958-505',
                'part_name' => 'UNI; FABRE;UNC;T187803;0.984IN(25MM);100ME;67R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                308 => 
                array (
                    'id' => 309,
                    'part_number' => '170172-502',
                    'part_name' => 'UNI GOLD',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                309 => 
                array (
                    'id' => 310,
                    'part_number' => '170430-502',
                'part_name' => 'UNI;FABRE;UNC;T187804;0.75IN(19.05MM);100ME;75R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                310 => 
                array (
                    'id' => 311,
                    'part_number' => '170430-503',
                'part_name' => 'UNI;FABRE;UNC;T187804;0.984IN(25MM);100ME;75R;SPLICING TAPE;MOQ;PHIL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                311 => 
                array (
                    'id' => 312,
                    'part_number' => '171052-503',
                'part_name' => 'UNI BLUE;SUW;T188408,0.75IN;(19.05MM);100ME;70R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                312 => 
                array (
                    'id' => 313,
                    'part_number' => '174172-502',
                'part_name' => 'UNI; RUBY; WCU; T906267; 0.75IN (19.05MM); 100ME; 67; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                313 => 
                array (
                    'id' => 314,
                    'part_number' => '174172-503',
                'part_name' => 'UNI; RUBY; WCU;T906267;0.9814IN (25MM);100ME; 67; SPLAICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                314 => 
                array (
                    'id' => 315,
                    'part_number' => '174172-504',
                'part_name' => 'UNI; RUBY; WCU; T906267; 0.63IN (16MM); 100ME; 67; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                315 => 
                array (
                    'id' => 316,
                    'part_number' => '174172-505',
                'part_name' => 'UNI;RUBY;WCU;T906267;0.394IN(10MM);100ME;67',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                316 => 
                array (
                    'id' => 317,
                    'part_number' => '174172-506',
                'part_name' => 'UNI;RUBY;UNC;SUW;T188475;0.866IN(22MM);100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                317 => 
                array (
                    'id' => 318,
                    'part_number' => '174172-507',
                'part_name' => 'UNI;RUBY;UNC;T906267;0.59IN(15MM);100ME;67',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                318 => 
                array (
                    'id' => 319,
                    'part_number' => '174172-522',
                'part_name' => 'UNI; RUBY; WCU; T906267; 0.75IN (19.05MM); 200ME; 67',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                319 => 
                array (
                    'id' => 320,
                    'part_number' => '174172-524',
                'part_name' => 'UNI;RUBY;UNC;T906267;0.63IN(16MM);200ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                320 => 
                array (
                    'id' => 321,
                    'part_number' => '174172-600',
                'part_name' => 'UNI RUBY;0.75IN(19.05MM);20ME ;67;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                321 => 
                array (
                    'id' => 322,
                    'part_number' => '175868-502',
                'part_name' => 'UNI; GREEN; SUW; T188667;0.75IN(19.05MM);100ME;67;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                322 => 
                array (
                    'id' => 323,
                    'part_number' => '175868-503',
                'part_name' => 'UNI; GREEN; SUW; T188667, 1.0IN (25.4MM); 100ME; 67',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                323 => 
                array (
                    'id' => 324,
                    'part_number' => '175868-504',
                'part_name' => 'UNI;GREEN;SUW;T188667;0.866IN(22MM);100ME;67;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                324 => 
                array (
                    'id' => 325,
                    'part_number' => '175902-502',
                'part_name' => 'UNI;BLUE;SUW;T188403;0.75IN(19MM);100ME;67R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                325 => 
                array (
                    'id' => 326,
                    'part_number' => '175902-503',
                'part_name' => 'UNI;BLUE;SUW;T188403;1.0IN (25.4MM);100ME;67R;SPLICING TAPE;;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                326 => 
                array (
                    'id' => 327,
                    'part_number' => '175902-505',
                'part_name' => 'UNI BL;SUW;0.75IN(19MM);67R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                327 => 
                array (
                    'id' => 328,
                    'part_number' => '175902-506',
                'part_name' => 'UNI;BLUE;SUW;T188403;0.63IN(16MM);100ME;67R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                328 => 
                array (
                    'id' => 329,
                    'part_number' => '176546-502',
                'part_name' => 'UNI;RUBY;WCU;T906275;0.75IN(19MM);100ME;75;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                329 => 
                array (
                    'id' => 330,
                    'part_number' => '176546-503',
                'part_name' => 'UNI;RUBY;UNC;T906275;0.984IN(25MM);100ME;75;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                330 => 
                array (
                    'id' => 331,
                    'part_number' => '176546-504',
                'part_name' => 'UNI; RUBY; WCU; T906203; 0.63IN (16MM); 100ME; 67R; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                331 => 
                array (
                    'id' => 332,
                    'part_number' => '176546-522',
                'part_name' => 'UNI;RUBY;UNC;T906275;0.75IN(19MM);200ME;75;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                332 => 
                array (
                    'id' => 333,
                    'part_number' => '176546-524',
                'part_name' => 'UNI;RBY;UNC;T906275;0.630IN(16MM);200M;75;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                333 => 
                array (
                    'id' => 334,
                    'part_number' => '176836-502',
                'part_name' => 'UNI; RUBY; WCU; T906255; 0.75IN (19MM); 100ME; 55; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                334 => 
                array (
                    'id' => 335,
                    'part_number' => '176836-504',
                'part_name' => 'UNI;RUBY;WCU;T906255;0.631IN(16MM);100ME;55;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                335 => 
                array (
                    'id' => 336,
                    'part_number' => '177716-502',
                'part_name' => 'UNI; PEARL; SUW; T907167; 0.75IN (19.05MM); 100ME; 67;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                336 => 
                array (
                    'id' => 337,
                    'part_number' => '177716-503',
                    'part_name' => 'UNI;PEARL 67;100ME',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                337 => 
                array (
                    'id' => 338,
                    'part_number' => '177716-505',
                'part_name' => 'UNI;PEARL;SUW;T907167;0.63IN(16MM);100ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                338 => 
                array (
                    'id' => 339,
                    'part_number' => '177716-522',
                'part_name' => 'UNI; PEARL; SUW; T907167; 0.75IN (19.05MM); 200ME; 67',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                339 => 
                array (
                    'id' => 340,
                    'part_number' => '177838-502',
                'part_name' => 'UNI; PEARL; SUW; T907155; 0.75IN(19MM); 100ME; 55;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                340 => 
                array (
                    'id' => 341,
                    'part_number' => '177838-504',
                'part_name' => 'UNI;PEARL;SUW;T907155;0.63IN(16MM);100ME;55;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                341 => 
                array (
                    'id' => 342,
                    'part_number' => '177840-502',
                'part_name' => 'UNI;PEARL;SUW;T907175;0.75IN(19MM);100ME;75;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                342 => 
                array (
                    'id' => 343,
                    'part_number' => '177840-505',
                'part_name' => 'UNI;PEARL;SUW;T907175;0.63IN(16MM);100ME;75;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                343 => 
                array (
                    'id' => 344,
                    'part_number' => '177840-508',
                'part_name' => 'UNI PEARL; SUW;T907175;0.827IN(21MM);100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                344 => 
                array (
                    'id' => 345,
                    'part_number' => '177882-502',
                'part_name' => 'UNI;BLUE;SUW;T188445;0.75IN(19.05MM);100ME;45;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                345 => 
                array (
                    'id' => 346,
                    'part_number' => '177882-503',
                'part_name' => 'UNI; BLUE; SUW; T188445; 1.0IN (25.4MM); 100ME; 45; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                346 => 
                array (
                    'id' => 347,
                    'part_number' => '177882-504',
                'part_name' => 'UNI;BLUE;SUW;T188445;0.866IN(22MM);100ME;45;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                347 => 
                array (
                    'id' => 348,
                    'part_number' => '177882-505',
                'part_name' => 'UNI;BLUE;SUW;T188445;0.984IN(25MM);100ME;45;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                348 => 
                array (
                    'id' => 349,
                    'part_number' => '177887-502',
                'part_name' => 'UNI;BLUE;SUW;T188445;0.75IN(19.05MM);100ME;80;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                349 => 
                array (
                    'id' => 350,
                    'part_number' => '177887-503',
                'part_name' => 'UNI;BLUE;SUW;T188480;1.0IN(25.4MM);100ME;80;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                350 => 
                array (
                    'id' => 351,
                    'part_number' => '177887-506',
                'part_name' => 'UNI;BLUE; SUW; T188480;0.866IN(22MM);100ME; 80;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                351 => 
                array (
                    'id' => 352,
                    'part_number' => '177888-502',
                'part_name' => 'UNI;GREEN; SUW;T188675;0.75IN(19.05MM);100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                352 => 
                array (
                    'id' => 353,
                    'part_number' => '177888-503',
                'part_name' => 'UNI;GREEN; SUW;T1888675;1.0IN(25.4MM);100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                353 => 
                array (
                    'id' => 354,
                    'part_number' => '177888-504',
                'part_name' => 'UNI; GREEN;SUW;T188675;0.63IN (16MM)100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                354 => 
                array (
                    'id' => 355,
                    'part_number' => '177888-505',
                'part_name' => 'UNI; GREEN; SUW; T188675; 0.866IN (22MM); 100ME; 75; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                355 => 
                array (
                    'id' => 356,
                    'part_number' => '177888-506',
                'part_name' => 'UNI; GREEN;SUW;T188675;0.39IN (10MM)100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                356 => 
                array (
                    'id' => 357,
                    'part_number' => '177888-522',
                'part_name' => 'UNI; GREEN; SUW; T188675; 0.75IN (19.05MM); 200ME; 75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                357 => 
                array (
                    'id' => 358,
                    'part_number' => '177961-502',
                'part_name' => 'UNI;BLUE;SUW;T188401;0.75IN(19.05MM);100ME;45R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                358 => 
                array (
                    'id' => 359,
                    'part_number' => '178003-502',
                'part_name' => 'UNI; GREEN; SUW; T188604; 0.75IN (19.05MM);100ME; 75R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                359 => 
                array (
                    'id' => 360,
                    'part_number' => '178003-503',
                'part_name' => 'UNI; GREEN; SUW; T188604;1.0IN (25.4MM);100ME ;75R',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                360 => 
                array (
                    'id' => 361,
                    'part_number' => '178065-502',
                'part_name' => 'UNI;GREEN;SUW;T188601;0.75IN(19.05MM);100ME',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                361 => 
                array (
                    'id' => 362,
                    'part_number' => '178077-502',
                'part_name' => 'UNI;BLUE;SUW;T188470;0.75IN(19.05MM);100ME;70;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                362 => 
                array (
                    'id' => 363,
                    'part_number' => '178077-503',
                'part_name' => 'UNI;BLUE;SUW;T188470;1.0IN(25.4MM);100ME;70;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                363 => 
                array (
                    'id' => 364,
                    'part_number' => '178077-504',
                'part_name' => 'UNI;BLUE;SUW;T188470;0.59IN(15MM);100ME;70;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                364 => 
                array (
                    'id' => 365,
                    'part_number' => '178077-505',
                'part_name' => 'UNI; BLUE; SUW; T188470;0.9841IN(25MM);200ME;70',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                365 => 
                array (
                    'id' => 366,
                    'part_number' => '178077-506',
                'part_name' => 'UNI;BLUE; SUW; T188470;0.984IN(25MM);100ME; 70;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                366 => 
                array (
                    'id' => 367,
                    'part_number' => '178129-502',
                'part_name' => 'UNI; PEARL; SUW; T907103; 0.75IN(19.05MM); 100ME; 67R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                367 => 
                array (
                    'id' => 368,
                    'part_number' => '178156-502',
                'part_name' => 'UNI GREEN;SUW;T188645;0.75IN(19.05MM);100ME;45;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                368 => 
                array (
                    'id' => 369,
                    'part_number' => '178273-502',
                'part_name' => 'UNI; BLUE; SUW; T188455;0.75IN (19.05MM);100ME;55;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                369 => 
                array (
                    'id' => 370,
                    'part_number' => '178273-503',
                'part_name' => 'UNI;BLUE; SUW;T188402;1.0IN(25.4MM);100ME;55R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                370 => 
                array (
                    'id' => 371,
                    'part_number' => '178273-504',
                'part_name' => 'UNI;BLUE;SUW;T188402;0.63IN(16MM);100ME;55R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                371 => 
                array (
                    'id' => 372,
                    'part_number' => '178288-502',
                'part_name' => 'UNI; BLUE;SUW;T188460;0.75IN(19MM);100ME;60',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                372 => 
                array (
                    'id' => 373,
                    'part_number' => '178420-502',
                'part_name' => 'UNI; GREEN; SUW; T188603;0.75IN (19MM);100ME;67R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                373 => 
                array (
                    'id' => 374,
                    'part_number' => '178420-503',
                'part_name' => 'UNI;GREEN;SUW;T188603;1.0IN(25.4MM);100ME;67R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                374 => 
                array (
                    'id' => 375,
                    'part_number' => '178420-504',
                'part_name' => 'UNI GR;SUW;T188603;0.75IN(19MM);200ME;67R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                375 => 
                array (
                    'id' => 376,
                    'part_number' => '178571-502',
                'part_name' => 'UNI;BLUE;SUW;T188403;1.0IN (25.4MM);100ME;67R;SPLICING TAPE;;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                376 => 
                array (
                    'id' => 377,
                    'part_number' => '178571-504',
                'part_name' => 'UNI;BLUE;SUW;T188490;1.0IN (25.4MM);100ME;90 ;SPLICING TAPE;;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                377 => 
                array (
                    'id' => 378,
                    'part_number' => '178576-502',
                'part_name' => 'UNI GREEN; SUW;T188680;0.75IN (19.05MM);100ME;80;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                378 => 
                array (
                    'id' => 379,
                    'part_number' => '178576-504',
                'part_name' => 'UNI;GREEN;SUW;T188680;0.866IN(22MM);100ME;80;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                379 => 
                array (
                    'id' => 380,
                    'part_number' => '178621-502',
                'part_name' => 'UNI;GREEN;SUW;T188602;0.75IN(19MM);100ME;55R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                380 => 
                array (
                    'id' => 381,
                    'part_number' => '178621-504',
                'part_name' => 'UNI;GREEN;SUW;T188602;1.0IN(25.4MM);100ME;55R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                381 => 
                array (
                    'id' => 382,
                    'part_number' => '179395-504',
                'part_name' => 'UNI;GOLD;UNC;T188375;0.63IN(16MM);100ME;75',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                382 => 
                array (
                    'id' => 383,
                    'part_number' => '179587-501',
                'part_name' => 'UNI ORANGE;WCU;T188175;0.63IN(16MM);100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                383 => 
                array (
                    'id' => 384,
                    'part_number' => '179587-502',
                'part_name' => 'UNI ORANGE;WCU;T188175;0.63IN(16MM);100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                384 => 
                array (
                    'id' => 385,
                    'part_number' => '179891-502',
                'part_name' => 'UNI;RUBY;WCU;T906204;0.75IN(19MM);100ME;75R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                385 => 
                array (
                    'id' => 386,
                    'part_number' => '179891-503',
                'part_name' => 'UNI;RUBY;WCU;T906204;0.63IN(16MM);100ME;75R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                386 => 
                array (
                    'id' => 387,
                    'part_number' => '179893-502',
                'part_name' => 'UNI; RUBY; WCU; T906203; 0.75IN (19.05MM); 100ME; 67R; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                387 => 
                array (
                    'id' => 388,
                    'part_number' => '179893-506',
                'part_name' => 'UNI;RUBY;UNC;T906203;0.63IN(16MM);100ME;67R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                388 => 
                array (
                    'id' => 389,
                    'part_number' => '179893-522',
                'part_name' => 'UNI;RBY;UNC;T906203;0.750IN(19MM);200M;67R;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                389 => 
                array (
                    'id' => 390,
                    'part_number' => '179896-502',
                'part_name' => 'UNI;PEARL; SUW; T188470;0.984IN(25MM);100ME; 70;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                390 => 
                array (
                    'id' => 391,
                    'part_number' => '179896-503',
                'part_name' => 'UNI;PEARL;SUW;T907104;0.63IN(16MM);100ME;75R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                391 => 
                array (
                    'id' => 392,
                    'part_number' => '180460-502',
                'part_name' => 'UNI; SAPPHIRE; SUW; 0.75IN (19.05MM); 100ME; 67;T907867;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                392 => 
                array (
                    'id' => 393,
                    'part_number' => '180824-501',
                'part_name' => 'UNI GREEN;SUW;T188645;1.0IN(25.4MM);100ME;45;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                393 => 
                array (
                    'id' => 394,
                    'part_number' => '180850-502',
                'part_name' => 'UNI;GOLD;SUW;T188372;0.63IN(16MM);100M;72;SPLICING TAPE;PHIL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                394 => 
                array (
                    'id' => 395,
                    'part_number' => '181087-505',
                'part_name' => 'UNI;YEL;UNC;T193001;0.394IN(10MM);100M;45R;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                395 => 
                array (
                    'id' => 396,
                    'part_number' => '182879-502',
                'part_name' => 'UNI; SAPPHIRE; SUW; T907855;0.75IN (19.05MM);100ME;55;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                396 => 
                array (
                    'id' => 397,
                    'part_number' => '183311-502',
                'part_name' => 'UNI;FAB;UNC;T187801;0.750IN(19MM);100ME;45R;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                397 => 
                array (
                    'id' => 398,
                    'part_number' => '183359-502',
                'part_name' => 'UNI;SAPPHIRE;SUW;T907875;SUW;0.75IN(19.05MM);100ME;75 SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                398 => 
                array (
                    'id' => 399,
                    'part_number' => '183359-504',
                'part_name' => 'UNI;SAPPHIRE;SUW;T907875;0.63IN(16MM);100ME;75;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                399 => 
                array (
                    'id' => 400,
                    'part_number' => '184573-502',
                'part_name' => 'UNI;RUBY;WCU;T906290;0.75IN(19MM);100ME;90;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                400 => 
                array (
                    'id' => 401,
                    'part_number' => '184824-502',
                'part_name' => 'UNI PINK;WCU;T188008;0.75IN;(19MM);70R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                401 => 
                array (
                    'id' => 402,
                    'part_number' => '184824-503',
                'part_name' => 'UNI;PINK;WCU;T188008;0.59IN(15MM);100ME;70R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                402 => 
                array (
                    'id' => 403,
                    'part_number' => '184825-502',
                'part_name' => 'UNI WHITE;SUW;T188208;0.75IN;(19MM);100ME;70R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                403 => 
                array (
                    'id' => 404,
                    'part_number' => '184825-504',
                'part_name' => 'UNI WHITE; UNC T188208;0.59IN(15MM);100M;70R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                404 => 
                array (
                    'id' => 405,
                    'part_number' => '184843-502',
                'part_name' => 'UNI YELLOW;UNC;T193070;0.75IN(19MM);100ME;70;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                405 => 
                array (
                    'id' => 406,
                    'part_number' => '184918-502',
                'part_name' => 'UNI;SAPPHIRE;SUW;T907803;0.75IN(19MM);100ME;67R; SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                406 => 
                array (
                    'id' => 407,
                    'part_number' => '186157-502',
                'part_name' => 'UNI;FABRE;UNC;T187870;0.75IN(19MM);100ME;70;MOQ;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                407 => 
                array (
                    'id' => 408,
                    'part_number' => '186196-504',
                'part_name' => 'UNI;PEARL;SUW;T907102;0.63IN(16MM);100ME;55R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                408 => 
                array (
                    'id' => 409,
                    'part_number' => '186197-504',
                'part_name' => 'UNI;RUBY;WCU;T906202;0.63IN(16MM);100ME;55R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                409 => 
                array (
                    'id' => 410,
                    'part_number' => '187553-502',
                'part_name' => 'UNI ORANGE;UNC;T188104;1.0IN (25.5MM);100ME;75R;SPLICING TAPE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                410 => 
                array (
                    'id' => 411,
                    'part_number' => '188447-502',
                'part_name' => 'UNI;BLUE;UNC;T909667;0.75IN(19MM);100ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                411 => 
                array (
                    'id' => 412,
                    'part_number' => '188447-503',
                'part_name' => 'UNI;BLUE;UNC;T909667;1.0IN(25.4MM);100ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                412 => 
                array (
                    'id' => 413,
                    'part_number' => '188447-504',
                'part_name' => 'UNI;BLUE;UNC;T909667;0.63IN(16MM);100ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                413 => 
                array (
                    'id' => 414,
                    'part_number' => '188447-505',
                'part_name' => 'UNI;BLUE;UNC;T909667;0.984IN(25MM);100ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                414 => 
                array (
                    'id' => 415,
                    'part_number' => '188564-502',
                'part_name' => 'UNI;BLUE;UNC;T909655;0.75IN(19MM);100ME;55;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                415 => 
                array (
                    'id' => 416,
                    'part_number' => '189380-502',
                'part_name' => 'UNI;PEARL;SUW;T907155;0.75IN(19MM);100ME;55;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                416 => 
                array (
                    'id' => 417,
                    'part_number' => '189381-502',
                'part_name' => 'UNI;PEARL;SUW;T907175;0.75IN(19MM);100ME;75;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                417 => 
                array (
                    'id' => 418,
                    'part_number' => '189381-503',
                'part_name' => 'UNI;PEARL;SUW;T907175;0.984IN(25MM);100ME;75;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                418 => 
                array (
                    'id' => 419,
                    'part_number' => '189381-505',
                'part_name' => 'UNI;PEARL;SUW;T907175;0.63IN(16MM);100ME;75;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                419 => 
                array (
                    'id' => 420,
                    'part_number' => '189381-508',
                'part_name' => 'UNI;PEARL;SUW;T907175;0.827IN(21MM);100ME;75;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                420 => 
                array (
                    'id' => 421,
                    'part_number' => '189381-525',
                'part_name' => 'UNI;PRL;UNC;T907175;0.630IN(16MM);200M;75;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                421 => 
                array (
                    'id' => 422,
                    'part_number' => '189382-502',
                'part_name' => 'UNI;PEARL;SUW;T907103;0.75IN(19MM);100ME;67R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                422 => 
                array (
                    'id' => 423,
                    'part_number' => '189382-506',
                'part_name' => 'UNI;PEARL;SUW;T907103;0.63N(16MM);100ME;67R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                423 => 
                array (
                    'id' => 424,
                    'part_number' => '189382-522',
                'part_name' => 'UNI;PEARL;SUW;T907103;0.75IN(19MM);200ME;67R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                424 => 
                array (
                    'id' => 425,
                    'part_number' => '189383-502',
                'part_name' => 'UNI;PEARL;SUW;T907167;0.75IN(19MM);100ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                425 => 
                array (
                    'id' => 426,
                    'part_number' => '189383-503',
                'part_name' => 'UNI;PEARL;SUW;T907167;1.0IN(25.4MM);100ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                426 => 
                array (
                    'id' => 427,
                    'part_number' => '189383-505',
                'part_name' => 'UNI;PEARL;SUW;T907167;0.63IN(16MM);100ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                427 => 
                array (
                    'id' => 428,
                    'part_number' => '189383-522',
                'part_name' => 'UNI;PEARL;SUW;T907167;0.75IN(19MM);200ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                428 => 
                array (
                    'id' => 429,
                    'part_number' => '189383-525',
                'part_name' => 'UNI;PEARL;SUW;T907167;0.63IN(16MM);200ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                429 => 
                array (
                    'id' => 430,
                    'part_number' => '189384-502',
                'part_name' => 'UNI;PEARL;SUW;T907103;0.75IN(19MM);100ME;75R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                430 => 
                array (
                    'id' => 431,
                    'part_number' => '189384-503',
                'part_name' => 'UNI;PEARL;SUW;T907104;0.63IN(16MM);100ME;75R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                431 => 
                array (
                    'id' => 432,
                    'part_number' => '190017-502',
                'part_name' => 'UNI;YELLOW;UNC;T193002;0.75IN(19MM);100ME;55R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                432 => 
                array (
                    'id' => 433,
                    'part_number' => '190155-502',
                'part_name' => 'UNI;BLUE;SUW;T188467;0.75IN(19MM);100ME;67;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                433 => 
                array (
                    'id' => 434,
                    'part_number' => '190798-504',
                'part_name' => 'UNI;PEARL;SUW;T907145;0.984IN(25MM);100ME;45;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                434 => 
                array (
                    'id' => 435,
                    'part_number' => '148654-504',
                'part_name' => 'UNI;PINK;UNC;T188001;0.394IN(10MM);100ME;45R;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                435 => 
                array (
                    'id' => 436,
                    'part_number' => '148572-509',
                'part_name' => 'UNI;PINK;WCU;T188067;0.472IN(12MM);100M;67',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                436 => 
                array (
                    'id' => 437,
                    'part_number' => '148575-509',
                'part_name' => 'UNI;PINK;UNC;T188075;0.472IN(12MM);100ME;75;CTO;SPLICING TAPE;PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                437 => 
                array (
                    'id' => 438,
                    'part_number' => '187197-001',
                    'part_name' => 'VALEO-Y150;PEDOT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                438 => 
                array (
                    'id' => 439,
                    'part_number' => '181578-001',
                    'part_name' => 'VALEO T966596-Y120 B',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                439 => 
                array (
                    'id' => 440,
                    'part_number' => '182191-001',
                    'part_name' => 'VISTEON COIL new rev',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                440 => 
                array (
                    'id' => 441,
                    'part_number' => '189132-001',
                    'part_name' => 'VISTEON;V9JPLF-18B966-AF;PSA D3 TELLTALE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                441 => 
                array (
                    'id' => 442,
                    'part_number' => '189214-001',
                    'part_name' => 'VISTEON; OPEL P2J20-P2Q0',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                442 => 
                array (
                    'id' => 443,
                    'part_number' => '189445-001',
                    'part_name' => 'VISTEON P2Q RIGHT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                443 => 
                array (
                    'id' => 444,
                    'part_number' => '189619-001',
                    'part_name' => 'VISTEON;V9JPLF-18B966-AG;PSA D3 TELLTALE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                444 => 
                array (
                    'id' => 445,
                    'part_number' => '190273-001',
                    'part_name' => 'VISTEON P2Q LEFT REV BG',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                445 => 
                array (
                    'id' => 446,
                    'part_number' => '190822-001',
                    'part_name' => 'Visteon ; VPMPLF-9G653-AC ; P5 DN10 ; Single-Sided ; PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                446 => 
                array (
                    'id' => 447,
                    'part_number' => '190965-001',
                    'part_name' => 'Visteon ; VPMPLF-9G653-AC ; P5 DN10',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                447 => 
                array (
                    'id' => 448,
                    'part_number' => '191109-001',
                'part_name' => 'VISTEON P5Q LEFT( SUB CKT )',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                448 => 
                array (
                    'id' => 449,
                    'part_number' => '191111-001',
                    'part_name' => 'Visteon ; VPMPLF-9G653-BD ; P5Q LEFT ; Single-Sided ; PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                449 => 
                array (
                    'id' => 450,
                    'part_number' => '191120-001',
                    'part_name' => 'Visteon ; VPMPLF-9G653-CD ; P5Q RIGHT ; Single-Sided ; PHL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                450 => 
                array (
                    'id' => 451,
                    'part_number' => '191121-001',
                'part_name' => 'VISTEON P5Q RIGHT ( SUB CKT )',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                451 => 
                array (
                    'id' => 452,
                    'part_number' => '191189-001',
                    'part_name' => 'VISTEON P5Q LEFT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                452 => 
                array (
                    'id' => 453,
                    'part_number' => '191190-001',
                    'part_name' => 'VISTEON P5Q RIGHT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                453 => 
                array (
                    'id' => 454,
                    'part_number' => '177175-001',
                    'part_name' => 'WITTE POWER LIFT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                454 => 
                array (
                    'id' => 455,
                    'part_number' => '178888-001',
                    'part_name' => 'Yura 8 Speed SBW new',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                455 => 
                array (
                    'id' => 456,
                    'part_number' => '182059-001',
                'part_name' => 'Yura Non SBW 8 Speed (NEW)',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                456 => 
                array (
                    'id' => 457,
                    'part_number' => '182839-001',
                    'part_name' => 'YURA ELTEC SBC',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                457 => 
                array (
                    'id' => 458,
                    'part_number' => '183376-001',
                    'part_name' => 'YURA GEN 2 P-SOL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                458 => 
                array (
                    'id' => 459,
                    'part_number' => '184999-001',
                    'part_name' => 'YURA ELTEC -LARGE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                459 => 
                array (
                    'id' => 460,
                    'part_number' => '185001-001',
                    'part_name' => 'YURA ELTEC -MEDIUM',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                460 => 
                array (
                    'id' => 461,
                    'part_number' => '188301-001',
                    'part_name' => 'YURA ELTEC; 8 SPEED M3 SBC',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                461 => 
                array (
                    'id' => 462,
                    'part_number' => '163441-001',
                    'part_name' => 'ZOLLNER/ OSRAM BMW MAIN',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                462 => 
                array (
                    'id' => 463,
                    'part_number' => '163504-001',
                    'part_name' => 'ZOLLNER/ OSRAM BMW RCL SIDE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                463 => 
                array (
                    'id' => 464,
                    'part_number' => '163513-001',
                    'part_name' => 'IC CKT;ZOLLNER/OSRAM;1209679-00; BENTLEY TAIL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                464 => 
                array (
                    'id' => 465,
                    'part_number' => '163518-001',
                    'part_name' => 'ZOLLNER/ OSRAM BENTLEY STOP',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                465 => 
                array (
                    'id' => 466,
                    'part_number' => '169926-001',
                    'part_name' => 'ZOLLNER BENTLEY TAIL ECE',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                466 => 
                array (
                    'id' => 467,
                    'part_number' => '171677-001',
                    'part_name' => 'ZOLLNER AUDI TT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                467 => 
                array (
                    'id' => 468,
                    'part_number' => '171946-001',
                    'part_name' => 'Zollner R8 DRL Left',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                468 => 
                array (
                    'id' => 469,
                    'part_number' => '171954-001',
                    'part_name' => 'Zollner R8 DRL Right',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                469 => 
                array (
                    'id' => 470,
                    'part_number' => '173174-001',
                    'part_name' => 'ZOLLNER Lamborghini Right',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                470 => 
                array (
                    'id' => 471,
                    'part_number' => '173178-001',
                    'part_name' => 'ZOLLNER Lamborghini Left',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                471 => 
                array (
                    'id' => 472,
                    'part_number' => '173902-001',
                    'part_name' => 'ZOLLNER ROVER L322',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                472 => 
                array (
                    'id' => 473,
                    'part_number' => '174061-001',
                    'part_name' => 'ZOLLNER T87 REAR LEFT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                473 => 
                array (
                    'id' => 474,
                    'part_number' => '174062-001',
                    'part_name' => 'ZOLLNER T87 REAR RIGHT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                474 => 
                array (
                    'id' => 475,
                    'part_number' => '177850-001',
                    'part_name' => 'ZOLLNER B81 DRL',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                475 => 
                array (
                    'id' => 476,
                    'part_number' => '179432-001',
                    'part_name' => 'ZOLLNER X250 DRL RH new',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                476 => 
                array (
                    'id' => 477,
                    'part_number' => '179435-001',
                    'part_name' => 'ZOLLNER X250 DRL LH new',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                477 => 
                array (
                    'id' => 478,
                    'part_number' => '180118-001',
                    'part_name' => 'Zollner R8 ABL Right new',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                478 => 
                array (
                    'id' => 479,
                    'part_number' => '180196-001',
                    'part_name' => 'Zollner R8 ABL Left new',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                479 => 
                array (
                    'id' => 480,
                    'part_number' => '180197-001',
                    'part_name' => 'Zollner R8 FL Left new',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                480 => 
                array (
                    'id' => 481,
                    'part_number' => '180198-001',
                    'part_name' => 'Zollner R8 FL Right new',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                481 => 
                array (
                    'id' => 482,
                    'part_number' => '180404-001',
                    'part_name' => 'ZOLLNER C217',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                482 => 
                array (
                    'id' => 483,
                    'part_number' => '180833-001',
                    'part_name' => 'ZOLLNER C217 NEW REV',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                483 => 
                array (
                    'id' => 484,
                    'part_number' => '181894-001',
                    'part_name' => 'ZOLLNER AUDI TT new',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                484 => 
                array (
                    'id' => 485,
                    'part_number' => '181910-001',
                    'part_name' => 'ZOLLNER AUDI R8 ABL RIGHT NEW',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                485 => 
                array (
                    'id' => 486,
                    'part_number' => '182110-001',
                    'part_name' => 'ZOLLNER FOG LEFT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                486 => 
                array (
                    'id' => 487,
                    'part_number' => '182126-001',
                    'part_name' => 'ZOLLNER FOG RIGHT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                487 => 
                array (
                    'id' => 488,
                    'part_number' => '183637-001',
                    'part_name' => 'ZOLLNER MIDDLE TAIL LEFT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                488 => 
                array (
                    'id' => 489,
                    'part_number' => '183652-001',
                    'part_name' => 'ZOLLNER MIDDLE TAIL RIGHT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                489 => 
                array (
                    'id' => 490,
                    'part_number' => '183662-001',
                    'part_name' => 'ZOLLNER FASCIA LEFT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                490 => 
                array (
                    'id' => 491,
                    'part_number' => '183680-001',
                    'part_name' => 'ZOLLNER FASCIA RIGHT',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                491 => 
                array (
                    'id' => 492,
                    'part_number' => '184500-001',
                    'part_name' => 'ZOLLNER TESLA X BODYSIDE RIGHT NEW',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                492 => 
                array (
                    'id' => 493,
                    'part_number' => '184502-001',
                    'part_name' => 'ZOLLNER TESLA X BODYSIDE LEFT NEW',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                493 => 
                array (
                    'id' => 494,
                    'part_number' => '184504-001',
                    'part_name' => 'ZOLLNER TESLA X LIFTGATE RIGHT NEW',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                494 => 
                array (
                    'id' => 495,
                    'part_number' => '184506-001',
                    'part_name' => 'ZOLLNER TESLA X LIFTGATE LEFT NEW',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                495 => 
                array (
                    'id' => 496,
                    'part_number' => '185693-001',
                'part_name' => 'ZOLLNER FOG LEFT (NEW)',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                496 => 
                array (
                    'id' => 497,
                    'part_number' => '185695-001',
                'part_name' => 'ZOLLNER FOG RIGHT (NEW)',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                497 => 
                array (
                    'id' => 498,
                    'part_number' => '186528-001',
                'part_name' => 'Zollner Audi Q5 Left (new)',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                498 => 
                array (
                    'id' => 499,
                    'part_number' => '186529-001',
                'part_name' => 'Zollner Audi Q5 Right (new)',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
                499 => 
                array (
                    'id' => 500,
                    'part_number' => '186678-001',
                    'part_name' => 'ZOLLNER AUDI R8 FL LEFT 1472203-05;SINGLE SIDED',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            ));
        \DB::table('parts')->insert(array (
            0 => 
            array (
                'id' => 501,
                'part_number' => '186679-001',
                'part_name' => 'ZOLLNER AUDI R8 FL RIGHT;SINGLE SIDED',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 502,
                'part_number' => '186680-001',
                'part_name' => 'ZOLLNER AUDI R8 DRL RIGHT ;SINGLE SIDED',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 503,
                'part_number' => '186681-001',
                'part_name' => 'ZOLLNER;1472198-05; AUDI R8 ABL RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 504,
                'part_number' => '186682-001',
                'part_name' => 'ZOLLNER AUDI TT S-LINE',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 505,
                'part_number' => '186826-001',
                'part_name' => 'Zollner ; 1599883-05 ; X250 RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 506,
                'part_number' => '186827-001',
                'part_name' => 'Zollner ; 1599884-05 ; X250 LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 507,
                'part_number' => '186828-001',
                'part_name' => 'Zollner ; 1599883-05 ; X250 RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 508,
                'part_number' => '186830-001',
                'part_name' => 'Zollner ; 1599884-05 ; X250 LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 509,
                'part_number' => '187046-001',
                'part_name' => 'ZOLLNER LANCIA 844DX',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 510,
                'part_number' => '187295-001',
                'part_name' => 'ZOLLNER LANCIA 844SX',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 511,
                'part_number' => '188650-001',
                'part_name' => 'Zollner ; 1746028-05 ; L550 DRL Left ; Single-Sided ; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 512,
                'part_number' => '188653-001',
                'part_name' => 'Zollner ; 1746028-05 ; L550 DRL Right ; Single-Sided ; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 513,
                'part_number' => '188849-001',
                'part_name' => 'ZOLLNER;1518307-02; ROVER L322 TURN',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 514,
                'part_number' => '184187-001',
                'part_name' => 'ZOLLNER TESLA X SIGNATURE UPPER RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 515,
                'part_number' => '184199-001',
                'part_name' => 'ZOLLNER TESLA X SIGNATURE LOWER RIGHT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 516,
                'part_number' => '184223-001',
                'part_name' => 'ZOLLNER TESLA X SIGNATURE LOWER LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 517,
                'part_number' => '184213-001',
                'part_name' => 'ZOLLNER TESLA X SIGNATURE UPPER LEFT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 518,
                'part_number' => '192630-001',
                'part_name' => 'RUFFLES LEFT ARTICULATION',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 519,
                'part_number' => '192649-001',
                'part_name' => 'RUFFLES RIGHT ARTICULATION',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 520,
                'part_number' => '192979-001',
                'part_name' => 'Grupo Antolin Rivian R1 Reading Light;420-100 Cap Touch Sensor',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 521,
                'part_number' => '193002-001',
                'part_name' => 'Grupo Antolin Rivian R1 Overhead Light;421-100 Cap Touch Sensor',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 522,
                'part_number' => '193022-001',
                'part_name' => 'Grupo Antolin Rivian R1 Overhead Light;421-200 Cap Touch Sensor',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 523,
                'part_number' => '192900-001',
                'part_name' => 'SUBCKT ; CKT ; LEAR; GM; BET BDU;PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 524,
                'part_number' => '192903-001',
                'part_name' => 'CKT ; Lear ; 2BDT41F00 ; Rev AA ; GM BET BDU ; String A Natural ; Double-Sided ; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 525,
                'part_number' => '192904-001',
                'part_name' => 'CKT ; Lear ; 2BDT31F00 ; Rev AA ; GM BET BDU ; String B Black ; Double-Sided ; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 526,
                'part_number' => '193236-001',
                'part_name' => 'CKT ; Lear ; 2BDT44F00 ; Rev AA ; GM BET BDU 44 ; String A White ; Double-Sided ; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 527,
                'part_number' => '193212-001',
                'part_name' => 'CKT ; Lear ; 2BDT34F00 ; Rev AA ; GM BET BDU 34 ; String B Black ; Double-Sided ; PHL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 528,
                'part_number' => '185191-001',
                'part_name' => 'CKT; MID - TRONIC WIESAUPLAST GMBH; M001.03 301009; 07; DL382; Single-Sided',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 529,
                'part_number' => '181565-001',
                'part_name' => 'MOLEX GASKET',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 530,
                'part_number' => '193194-001',
                'part_name' => 'CKT; CUBEWORKS; NANOTAG 900 MHz A; SINGLE SIDED; ASSY; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 531,
                'part_number' => '193348-001',
                'part_name' => 'CKT; CUBEWORKS; ACSIP T EU868; COPPER; ASSEMBLY; NT',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}