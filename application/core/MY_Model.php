<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	protected $table = '';
	protected $primary_key = 'id';
	protected $order_by = 'id DESC';

	public function get_all($limit = NULL, $offset = 0)
	{
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}

		if ($this->order_by)
		{
			$this->db->order_by($this->order_by);
		}

		return $this->db->get($this->table)->result();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where($this->table, array($this->primary_key => $id))->row();
	}

	public function get_by($where, $single = FALSE)
	{
		$query = $this->db->get_where($this->table, $where);

		return $single ? $query->row() : $query->result();
	}

	public function count_all($where = array())
	{
		if (!empty($where))
		{
			$this->db->where($where);
		}

		return (int) $this->db->count_all_results($this->table);
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);

		return (int) $this->db->insert_id();
	}

	public function update($id, $data)
	{
		return $this->db->where($this->primary_key, $id)->update($this->table, $data);
	}

	public function delete($id)
	{
		return $this->db->delete($this->table, array($this->primary_key => $id));
	}

	public function get_latest($limit = 5, $where = array())
	{
		if (!empty($where))
		{
			$this->db->where($where);
		}

		if ($this->order_by)
		{
			$this->db->order_by($this->order_by);
		}

		return $this->db->limit($limit)->get($this->table)->result();
	}

	public function save($data, $id = NULL)
	{
		if ($id === NULL)
		{
			return $this->insert($data);
		}

		$this->update($id, $data);

		return $id;
	}

	public function get_for_dropdown($key = 'id', $value = 'name', $placeholder = NULL)
	{
		$options = array();

		if ($placeholder !== NULL)
		{
			$options[''] = $placeholder;
		}

		foreach ($this->get_all() as $item)
		{
			$options[$item->{$key}] = $item->{$value};
		}

		return $options;
	}
}