<?php
// model/group.php - Group messaging system
class TempGroupDB {
    private static $groups = [];
    private static $group_messages = [];
    private static $next_group_id = 1;
    private static $next_message_id = 1;
    
    public static function init() {
        if (empty(self::$groups)) {
            self::$groups = [
                [
                    'id' => 1,
                    'name' => 'Programmation',
                    'description' => 'Discussions sur la programmation et le développement',
                    'subject' => 'Informatique',
                    'member_count' => 24,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
                ],
                [
                    'id' => 2,
                    'name' => 'Mathématiques',
                    'description' => 'Aide et discussions en mathématiques',
                    'subject' => 'Maths',
                    'member_count' => 18,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-25 days'))
                ],
                [
                    'id' => 3,
                    'name' => 'Physique-Chimie',
                    'description' => 'Échanges sur la physique et la chimie',
                    'subject' => 'Sciences',
                    'member_count' => 15,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-20 days'))
                ],
                [
                    'id' => 4,
                    'name' => 'Histoire-Géo',
                    'description' => 'Discussions historiques et géographiques',
                    'subject' => 'Humanités',
                    'member_count' => 12,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-15 days'))
                ],
                [
                    'id' => 5,
                    'name' => 'Langues Étrangères',
                    'description' => 'Pratique des langues étrangères',
                    'subject' => 'Langues',
                    'member_count' => 20,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-10 days'))
                ],
                [
                    'id' => 6,
                    'name' => 'Projets Étudiants',
                    'description' => 'Coordination des projets étudiants',
                    'subject' => 'Projets',
                    'member_count' => 32,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
                ]
            ];
            
            // Sample group messages
            self::$group_messages = [
                [
                    'id' => 1,
                    'group_id' => 1,
                    'user_id' => 2,
                    'username' => 'Marie',
                    'content' => 'Quelqu\'un peut m\'aider avec un problème en Python?',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
                ],
                [
                    'id' => 2,
                    'group_id' => 1,
                    'user_id' => 3,
                    'username' => 'Pierre',
                    'content' => 'Bien sûr! Quel est le problème?',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
                ],
                [
                    'id' => 3,
                    'group_id' => 2,
                    'user_id' => 4,
                    'username' => 'Sophie',
                    'content' => 'Quelqu\'un a compris le dernier cours sur les intégrales?',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
                ],
                [
                    'id' => 4,
                    'group_id' => 3,
                    'user_id' => 5,
                    'username' => 'Thomas',
                    'content' => 'Qui veut réviser pour l\'examen de chimie ensemble?',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours'))
                ]
            ];
            self::$next_message_id = 5;
        }
    }
    
    public static function getAllGroups() {
        self::init();
        return self::$groups;
    }
    
    public static function getGroup($group_id) {
        self::init();
        foreach (self::$groups as $group) {
            if ($group['id'] == $group_id) {
                return $group;
            }
        }
        return null;
    }
    
    public static function getGroupMessages($group_id) {
        self::init();
        return array_filter(self::$group_messages, function($msg) use ($group_id) {
            return $msg['group_id'] == $group_id;
        });
    }
    
    public static function addGroupMessage($group_id, $user_id, $username, $content) {
        self::init();
        $new_message = [
            'id' => self::$next_message_id++,
            'group_id' => $group_id,
            'user_id' => $user_id,
            'username' => $username,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s')
        ];
        self::$group_messages[] = $new_message;
        return $new_message;
    }
    
    public static function searchGroups($query) {
        self::init();
        $results = [];
        
        foreach (self::$groups as $group) {
            if (stripos($group['name'], $query) !== false || 
                stripos($group['description'], $query) !== false ||
                stripos($group['subject'], $query) !== false) {
                $results[] = $group;
            }
        }
        
        return $results;
    }
}

// Functions
function getAllGroups() {
    return TempGroupDB::getAllGroups();
}

function getGroup($group_id) {
    return TempGroupDB::getGroup($group_id);
}

function getGroupMessages($group_id) {
    return TempGroupDB::getGroupMessages($group_id);
}

function addGroupMessage($group_id, $user_id, $username, $content) {
    return TempGroupDB::addGroupMessage($group_id, $user_id, $username, $content);
}

function searchGroups($query) {
    return TempGroupDB::searchGroups($query);
}
?>