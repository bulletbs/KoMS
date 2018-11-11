<?
class Database_Query_Builder_Select extends Kohana_Database_Query_Builder_Select {

	protected $_use_index = NULL;

	public function use_index($index)
	{
		$this->_use_index = $index;

		return $this;
	}

	public function compile($db = NULL)
	{
		if ( ! is_object($db))
		{
			// Get the database instance
			$db = Database::instance($db);
		}

		// Callback to quote columns
		$quote_column = array($db, 'quote_column');

		// Callback to quote tables
		$quote_table = array($db, 'quote_table');

		// Start a selection query
		$query = 'SELECT ';

		if ($this->_distinct === TRUE)
		{
			// Select only unique results
			$query .= 'DISTINCT ';
		}

		if (empty($this->_select))
		{
			// Select all columns
			$query .= '*';
		}
		else
		{
			// Select all columns
			$query .= implode(', ', array_unique(array_map($quote_column, $this->_select)));
		}

		if ( ! empty($this->_from))
		{
			// Set tables to select from
			$query .= ' FROM '.implode(', ', array_unique(array_map($quote_table, $this->_from)));
		}

		if ($this->_use_index !== NULL)
		{
			// Use index
			$query .= " USE INDEX (" . $this->_use_index . ")";
		}

		if ( ! empty($this->_join))
		{
			// Add tables to join
			$query .= ' '.$this->_compile_join($db, $this->_join);
		}

		if ( ! empty($this->_where))
		{
			// Add selection conditions
			$query .= ' WHERE '.$this->_compile_conditions($db, $this->_where);
		}

		if ( ! empty($this->_group_by))
		{
			// Add grouping
			$query .= ' '.$this->_compile_group_by($db, $this->_group_by);
		}

		if ( ! empty($this->_having))
		{
			// Add filtering conditions
			$query .= ' HAVING '.$this->_compile_conditions($db, $this->_having);
		}

		if ( ! empty($this->_order_by))
		{
			// Add sorting
			$query .= ' '.$this->_compile_order_by($db, $this->_order_by);
		}

		if ($this->_limit !== NULL)
		{
			// Add limiting
			$query .= ' LIMIT '.$this->_limit;
		}

		if ($this->_offset !== NULL)
		{
			// Add offsets
			$query .= ' OFFSET '.$this->_offset;
		}

		if ( ! empty($this->_union))
		{
			foreach ($this->_union as $u) {
				$query .= ' UNION ';
				if ($u['all'] === TRUE)
				{
					$query .= 'ALL ';
				}
				$query .= $u['select']->compile($db);
			}
		}

		$this->_sql = $query;

		return Kohana_Database_Query::compile($db);
	}

}