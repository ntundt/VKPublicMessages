<?php

class DataContainer {
	function __construct() {
		$this->data = [];
	}
	function addData($data) {
		for ($i = 0; $i < count($data); $i++) {
			$this->data[] = new UserOrGroup($data[$i]);
		}
	}
	function getObjById($id) {
		for ($i = 0; $i < count($this->data); $i++) {
			if ($this->data[$i]->id == $id or $this->data[$i]->id == -$id) {
				return $this->data[$i];
			}
		}
		return false;
	}
}

class UserOrGroup {
	function __construct($data) {
		$this->data = $data;
		$this->id = $data['id'];
	}
	function getName() {
		if (isset($this->data['name'])) {
			return $this->data['name'];
		} else {
			return $this->data['first_name'] . ' ' . $this->data['last_name'];
		}
	}
	function getFirstName() {
		if (isset($this->data['name'])) {
			return $this->data['name'];
		} else {
			return $this->data['first_name'];
		}	
	}
	function getProfilePicture() {
		if (isset($this->data['photo_100'])) {
			return $this->data['photo_100'];
		} else if (isset($this->data['photo_50'])) {
			return $this->data['photo_50'];
		} else {
			return 'images/soviet_50.png';
		}
	}
	function getNameAcc() {
		if (isset($this->data['first_name_acc']) and isset($this->data['last_name_acc'])) {
			return $this->data['first_name_acc'] . ' ' . $this->data['last_name_acc'];
		} else if (isset($this->data['first_name']) and isset($this->data['last_name'])) {
			return 'пользователя ' . $this->data['first_name'] . ' ' . $this->data['last_name'];
		} else {
			return 'пользователя';
		}
	}
	function getSex() {
		if (isset($this->data['sex'])) {
			return $this->data['sex'];
		} else {
			return 0;
		}
	}
}