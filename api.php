<?php

class PipeDriveApi {
    private $endpoint;
    private $token;

    public function __construct($token, $company_domain)
    {
        $this->endpoint = 'https://'.$company_domain.'.pipedrive.com/v1/';
        $this->token = $token;
    }

    private function Request($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);

        return $result = json_decode($output, true);
    }

    public function getOrganizations() {
        $url = $this->endpoint . 'organizations?api_token=' . $this->token;

        return $this->Request($url);
    }

    public function getPersons() {
        $url = $this->endpoint . 'persons?api_token=' . $this->token;

        return $this->Request($url);
    }

    public function getDeals() {
        $url = $this->endpoint . 'deals?api_token=' . $this->token;

        return $this->Request($url);
    }

    public function getNotes() {
        $url = $this->endpoint . 'notes?api_token=' . $this->token;

        return $this->Request($url);
    }

    public function getTasks() {
        $url = $this->endpoint . 'activities?api_token=' . $this->token;

        return $this->Request($url);
    }
}

$token = 'b8bcc6fce6df8e9ba0871e0a4aea4759ee12fb18';
$company_domain = 'vladyslavdudkatest-8ae408';
$api = new PipeDriveApi($token, $company_domain);


$orgs = $api->getOrganizations();
if ($orgs['success']) {
    if (!empty($orgs['data'])) {
        echo '<h2>Organizations</h2>';
        foreach ($orgs['data'] as $org) {
            echo '<ul>';
            echo '<li>';
            echo 'Name: ' . $org['name'];
            echo '</li>';
            echo '<li>';
            echo 'Owner: ' . $org['owner_name'];
            echo '</li>';
            echo '<li>';
            echo 'Address: ' . $org['address'];
            echo '</li>';
            echo '</ul>';
        }
    } else { echo 'No organizations created yet</br>'; }
} else { echo 'Organizations Error: ' . $orgs['error'] . '</br>'; }


$persons = $api->getPersons();
if ($persons['success']) {
    if (!empty($persons['data'])) {
        echo '<h2>Persons</h2>';
        foreach ($persons['data'] as $person) {
            echo '<ul>';
            echo '<li>';
            echo 'Name: ' . $person['name'];
            echo '</li>';
            echo '<li>';
            echo 'Owner: ' . $person['owner_name'];
            echo '</li>';
            echo '<li>';
            echo 'Organization: ' . $person['org_name'];
            echo '</li>';
            if ($person['phone'][0]['value'] != '') {
                echo '<li>';
                echo 'Phones:';
                foreach ($person['phone'] as $phone) {
                    echo '<ul style="margin-top: 6px;">';
                    echo '<li>';
                    echo 'Phone: ' . $phone['value'];
                    echo '</li>';
                    echo '<li>';
                    echo 'Label: ' . $phone['label'];
                    echo '</li>';
                    echo '</ul>';
                }
                echo '</li>';
            }
            if ($person['email'][0]['value'] != '') {
                echo '<li>';
                echo 'Emails:';
                foreach ($person['email'] as $email) {
                    echo '<ul style="margin-top: 6px;">';
                    echo '<li>';
                    echo $email['value'];
                    echo '</li>';
                    echo '</ul>';
                }
                echo '</li>';
            }
            echo '</ul></br>';
        }
    } else { echo 'No persons created yet</br>'; }
} else { echo 'Persons Error: ' . $persons['error'] . '</br>'; }

$deals = $api->getDeals();
if ($deals['success']) {
    if (!empty($deals['data'])) {
        echo '<h2>Deals</h2>';
        $notes = $api->getNotes();
        $tasks = $api->getTasks();
        foreach ($deals['data'] as $deal) {
            echo '<ul>';
            echo '<li>';
            echo 'Title: ' . $deal['title'];
            echo '</li>';
            echo '<li>';
            echo 'Person: ' . $deal['person_name'];
            echo '</li>';
            echo '<li>';
            echo 'Organization: ' . $deal['org_name'];
            echo '</li>';
            echo '<li>';
            echo 'Money: ' . $deal['value'] . ' ' . $deal['currency'];
            echo '</li>';
            echo '<li>';
            echo 'Status: ' . $deal['status'];
            echo '</li>';

            echo '<li>';
            echo 'Notes: ';
            $count = 0;
            foreach ($notes['data'] as $note) {
                if ($note['deal_id'] == $deal['id']) {
                    $count++;
                    echo '<ul style="margin-top: 6px;">';
                    echo '<li>';
                    echo 'Content: ' . $note['content'];
                    echo '</li>';
                    echo '<li>';
                    echo 'Add time: ' . $note['add_time'];
                    echo '</li>';
                    echo '</ul>';
                }
            }
            if ($count == 0 || empty($notes['data']))
                echo 'No notes created yet';
            echo '</li>';

            echo '<li>';
            echo 'Tasks: ';
            $count = 0;
            foreach ($tasks['data'] as $task) {
                if ($task['org_id'] == $deal['id']) {
                    $count++;
                    echo '<ul style="margin-top: 6px;">';
                    echo '<li>';
                    echo 'Type: ' . $task['type'];
                    echo '</li>';
                    echo '<li>';
                    echo 'Subject: ' . $task['subject'];
                    echo '</li>';
                    echo '<li>';
                    echo 'Date: ' . $task['due_date'] . ', in ' . $task['due_time'] . ' | Duration: '. $task['duration'];
                    echo '</li>';
                    echo '</ul>';
                }
            }
            if ($count == 0 || empty($tasks['data']))
                echo 'No tasks created yet';
            echo '</li>';

            echo '</ul></br>';
        }
    } else { echo 'No deals created yet</br>'; }
} else { echo 'Deals Error: ' . $deals['error'] . '</br>'; }

$tasks = $api->getTasks();
if ($tasks['success']) {
    if (!empty($tasks['data'])) {
        echo '<h2>Tasks</h2>';
        foreach ($tasks['data'] as $task) {
            echo '<ul>';
            echo '<li>';
            echo 'Subject: ' . $task['subject'];
            echo '</li>';
            echo '<li>';
            echo 'Type: ' . $task['type'];
            echo '</li>';
            echo '<li>';
            echo 'Organization: ' . $task['org_name'];
            echo '</li>';
            echo '<li>';
            echo 'Person: ' . $task['person_name'];
            echo '</li>';
            echo '</ul>';
        }
    } else { echo 'No tasks created yet</br>'; }
} else { echo 'Tasks Error: ' . $tasks['error'] . '</br>'; }
