<?php

/**
 * TDProject_Project_Model_Actions_Project_CalculationExport
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

// necessary because of invalid file structure created by PEAR installer
set_include_path(get_include_path() . PATH_SEPARATOR . 'app/code/lib/PHPExcel');

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Project_Model_Actions_Project_CalculationExport
    extends TDProject_Core_Model_Actions_Abstract {

	/**
	 * Path to the directory to store the calculation files
	 * @var string
	 */
	const DIRECTORY = '/Projects/CalculationExport/';

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Channel_Model_Actions_Category
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Project_Model_Actions_Project_CalculationExport($container);
    }
    
    /**
     * Returns the PEAR system configuration instance.
     * 
     * @return PEAR_Config The PEAR system configuration
     */
    protected function _getSystemConfig()
    {
    	return $this->getContainer()->getSystemConfig();
    }

	/**
	 * Loads the media directory from the system settings.
	 *
	 * @return string The path to the media directory
	 */
	protected function _getMediaDirectory()
	{
		// load the data directory
		$dataDir = $this->_getSystemConfig()->get('data_dir');
		// initialize a new LocalHome to load the settings
		$settings = TDProject_Core_Model_Utils_SettingUtil::getHome($this->getContainer())
			->findAll();
		// return the directory for storing media data
		foreach ($settings as $setting) {
			return $dataDir . DIRECTORY_SEPARATOR .  $setting->getMediaDirectory();
		}
	}

	/**
	 * Concatenates the passed filename to the path
	 * with the media directory and returns it.
	 *
	 * @param string $filename The filename of the calculation
	 * @return The full path to store the file
	 */
	protected function _getFilename($filename)
	{
		return $this->_getMediaDirectory() . self::DIRECTORY . $filename;
	}

	/**
	 * This method exports the tasks with the loggings and the
	 * estimations into an Excel Sheet for project with the ID
	 * passed as parameter.
	 *
	 * @param TechDivision_Lang_Integer $projectId
	 * 		The ID of the project to run the calculation for
	 * @param TechDivision_Lang_Integer $userId
	 * 		The ID of the user who starts the calculation
	 * @return TDProject_Project_Common_ValueObjects_ProjectCalculationExportLightValue
	 * 		The LVO with the calculation data
	 */
	public function export(
		TechDivision_Lang_Integer $projectId,
		TechDivision_Lang_Integer $userId) {
		// load the user
		$user = TDProject_Core_Model_Utils_UserUtil::getHome($this->getContainer())
			->findByPrimaryKey($userId);
		// load the project
		$project = TDProject_Project_Model_Utils_ProjectUtil::getHome($this->getContainer())
			->findByPrimaryKey($projectId);
		// initialize the filename
		$filename = $this->_getFilename(
			$projectId . '-' . date('Ymd-His', time()) . '.xlsx'
		);
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		// load the Excel Sheet's properties
		$properties = $objPHPExcel->getProperties();
		// set the meta information
		$properties->setCreator($user->getUsername());
		$properties->setLastModifiedBy($user->getUsername());
		$properties->setTitle("Calculation for " . $project->getName());
		$properties->setSubject("Project Calculation");
		$properties->setDescription("Calculation for project " . $project->getName());
		$properties->setKeywords($project->getName() . ' ' . $user->getUsername());
		$properties->setCategory("Project Calcuation");
		// set the actual workingsheet
		$objPHPExcel->setActiveSheetIndex(0);
		// set the font to use
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
		// load the projects tasks
		$tasks = TDProject_Project_Model_Utils_TaskUtil::getHome($this->getContainer())
			->findAllByProjectIdFkBranched($projectId);
		// load the Home for loading the tasks performance
		$home = TDProject_Project_Model_Utils_TaskPerformanceUtil::getHome($this->getContainer());
		// load the active sheet
		$sheet = $objPHPExcel->getActiveSheet();
		// initialize the row counter
		$row = 1;
		// set the headers values
		$sheet->setCellValue("A$row", 'Task-ID');
		$sheet->setCellValue("B$row", 'Task');
		$sheet->setCellValue("C$row", 'Abrechenbar');
		$sheet->setCellValue("D$row", 'Überbucht (h)');
		$sheet->setCellValue("E$row", 'Überbucht (%)');
		$sheet->setCellValue("F$row", 'Geschätzt');
		$sheet->setCellValue("G$row", 'Total, abrechenbar');
		$sheet->setCellValue("H$row", 'Total, nicht abrechenbar');
		$sheet->setCellValue("I$row", 'Status (%)');
		$sheet->setCellValue("J$row", 'Ausstehend (h)');
		// set the headers styles
		$sheet->getStyle("A$row:B$row")->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("C$row:J$row")->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$sheet->getStyle("A$row:J$row")->getFont()->setBold(true);
		// assemble the calculation
		foreach ($tasks as $counter => $task) {
			// load the task's performance
			$taskPerformances =
				$home->findAllByTaskIdFk($taskId = $task->getTaskId());
			// initialize the row counter
			$row++;
			// intialize the style
			$styleArray = array(
				'alignment' => array(
					'indent' => $task->getLevel()->intValue(),
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
				)
			);
			// load the tasks performance
			foreach ($taskPerformances as $taskPerformance) {
				// initialize the total and the minimum
				$minimum = 0;
				$totalBillable = 0;
				$totalNotBillable = 0;
				// check if a bookable task was found
				if ($task->getTaskTypeIdFk()->equals(new TechDivision_Lang_Integer(6))) {
					// calculate the minimum estimation
					$minimum = $this->toHours($taskPerformance->getMinimum());
					// check if the task is billable or not
					if ($task->getBillable()->booleanValue()) {
						$totalBillable = $this->toHours($taskPerformance->getTotal());
					} else {
						$totalNotBillable = $this->toHours($taskPerformance->getTotal());
					}
				}
				// set the cell's value
				$sheet->setCellValue("A$row", $taskPerformance->getTaskIdFk()->intValue());
				$sheet->setCellValue("B$row", $task->getName()->stringValue());
				$sheet->setCellValue("C$row", $task->getBillable()->__toString());
				$sheet->setCellValue("D$row", $this->_getOverbookedHours($task, $row));
				$sheet->setCellValue("E$row", "=IF(F$row>0,D$row*100/F$row,0)");
				$sheet->setCellValue("F$row", $minimum);
				$sheet->setCellValue("G$row", $totalBillable);
				$sheet->setCellValue("H$row", $totalNotBillable);
				$sheet->setCellValue("I$row", $this->_getStatus($task, $taskPerformance, $row));
				$sheet->setCellValue("J$row", $this->_getOutstanding($task, $taskPerformance, $row));
				// set the outline
				$sheet->getRowDimension($row)
					->setOutlineLevel($task->getLevel()->intValue());
				// apply the styles
				$sheet->getStyle("A$row:B$row")->applyFromArray($styleArray);
			}
		}
		// render the footer with the totals
		$sheet->setCellValue('B' . ($row + 1), "Totals");
		$sheet->setCellValue('D' . ($row + 1), "=SUM(D2:D$row)");
		$sheet->setCellValue('F' . ($row + 1), "=SUM(F2:F$row)");
		$sheet->setCellValue('G' . ($row + 1), "=SUM(G2:G$row)");
		$sheet->setCellValue('H' . ($row + 1), "=SUM(H2:H$row)");
		$sheet->setCellValue('J' . ($row + 1), "=SUM(J2:J$row)");
		// set the number format for the float columns
		$sheet->getStyle("D2:D$row")->getNumberFormat()->setFormatCode('#,##0.00');
		$sheet->getStyle("E2:E$row")->getNumberFormat()->setFormatCode('#,##0.00');
		$sheet->getStyle("I2:I$row")->getNumberFormat()->setFormatCode('#,##0.00');
		$sheet->getStyle("J2:J$row")->getNumberFormat()->setFormatCode('#,##0.00');
		// resize the columns to fit the values
		$sheet->getColumnDimension('A')->setAutosize(true);
		$sheet->getColumnDimension('B')->setAutosize(true);
		$sheet->getColumnDimension('C')->setAutosize(true);
		$sheet->getColumnDimension('D')->setAutosize(true);
		$sheet->getColumnDimension('E')->setAutosize(true);
		$sheet->getColumnDimension('F')->setAutosize(true);
		$sheet->getColumnDimension('G')->setAutosize(true);
		$sheet->getColumnDimension('H')->setAutosize(true);
		$sheet->getColumnDimension('I')->setAutosize(true);
		$sheet->getColumnDimension('J')->setAutosize(true);
		// create the Writer instance
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
		$objWriter->save($filename);
		// create a new calculation
		$calculation = TDProject_Project_Model_Utils_ProjectCalculationExportUtil
			::getHome($this->getContainer())->epbCreate();
		// set the data
		$calculation->setProjectIdFk($projectId);
		$calculation->setUserIdFk($userId);
		$calculation->setUsername($user->getUsername());
		$calculation->setFilename(new TechDivision_Lang_String($filename));
		$calculation->setCreatedAt(new TechDivision_Lang_Integer(time()));
		// store the calculation
		$calculation->create();
		// return the calculation's LVO
		return $calculation->getLightValue();
	}

	/**
	 * Returns and calculates the tasks status.
	 *
	 * @param TDProject_Project_Model_Entities_Task $task
	 * 		The task to calculate the status for
	 * @param TDProject_Project_Model_Entities_TaskPerformance $taskPerformance
	 * 		The task's performance
	 * @param integer $row The actual row, necessary for set the formular
	 * @return integer The task's status in percent
	 */
	protected function _getStatus(
		TDProject_Project_Model_Entities_Task $task,
		TDProject_Project_Model_Entities_TaskPerformance $taskPerformance,
		$row) {
		// check if the task has been marked as finished
		if ($taskPerformance->getFinished()->booleanValue()) {
			$status = "finished";
		} else {
			// check if the task is billable or not
			if ($task->getBillable()->booleanValue()) {
				$status = "=IF(F$row>0,IF(G$row*100/F$row>100,100,G$row*100/F$row),0)";
			} else {
				$status = "=IF(F$row>0,IF(H$row*100/F$row>100,100,H$row*100/F$row),0)";
			}
		}
		// return the status
		return $status;
	}

	/**
	 * Returns and outstanding amount of hours to go.
	 *
	 * @param TDProject_Project_Model_Entities_Task $task
	 * 		The task to calculate the status for
	 * @param TDProject_Project_Model_Entities_TaskPerformance $taskPerformance
	 * 		The task's performance
	 * @param integer $row The actual row, necessary for set the formular
	 * @return integer The task's status in percent
	 */
	protected function _getOutstanding($task, $taskPerformance, $row)
	{
		// check if the task has been marked as finished
		if ($taskPerformance->getFinished()->booleanValue()) {
			$outstanding = 0;
		} else {
			// check if the task is billable or not
			if ($task->getBillable()->booleanValue()) {
				$outstanding = "=IF(F$row-G$row<0,0,F$row-G$row)";
			} else {
				$outstanding = "=IF(F$row-H$row<0,0,F$row-H$row)";
			}
		}
		// return the outstanding hours
		return $outstanding;
	}

	/**
	 * Returns the formular for the hours a task is overbooked.
	 *
	 * @param TDProject_Project_Model_Entities_Task $task
	 * 		The task to calculate the hours for
	 * @param integer $row The actual row, necessary for set the formular
	 * @return integer The formular for the hours a task is overbooked
	 */
	protected function _getOverbookedHours($task, $row)
	{
		// check if the task is billable or not
		if ($task->getBillable()->booleanValue()) {
			return "=IF(G$row>F$row,G$row-F$row,0)";
		} else {
			return "=IF(H$row>F$row,H$row-F$row,0)";
		}
	}

	/**
	 * Calculates the hours for the passed seconds.
	 *
	 * @param TechDivision_Lang_Integer $seconds
	 * 		The seconds to calculate the hours for
	 * @return float The calculated hours
	 */
	public function toHours(TechDivision_Lang_Integer $seconds)
	{
		return round($seconds->intValue() / 3600, 2);
	}
}