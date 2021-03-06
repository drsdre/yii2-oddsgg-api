<?php

use yii\db\Schema;
use yii\db\Migration;

use drsdre\OddsGG\models\OddsGGSport;
use drsdre\OddsGG\models\OddsGGLeague;
use drsdre\OddsGG\models\OddsGGTournament;
use drsdre\OddsGG\models\OddsGGTeam;
use drsdre\OddsGG\models\OddsGGMatch;
use drsdre\OddsGG\models\OddsGGMarket;
use drsdre\OddsGG\models\OddsGGOdd;

class m170110_090000_init extends Migration {

	public function up() {
		$tableOptions = null;
		if ( $this->db->driverName === 'mysql' ) {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable( OddsGGSport::tableName(), [
			'id'         => $this->primaryKey(),
			'Name'       => $this->string( 255 )->notNull(),
			'created_at' => $this->integer( 11 )->notNull(),
			'updated_at' => $this->integer( 11 )->notNull(),
		], $tableOptions );

		$this->createTable( OddsGGLeague::tableName(), [
			'id'           => $this->primaryKey(),
			'CategoryName' => $this->string( 255 )->notNull(),
			'SportId'      => $this->integer( 11 )->notNull(),
			'created_at'   => $this->integer( 11 )->notNull(),
			'updated_at'   => $this->integer( 11 )->notNull(),
		], $tableOptions );

		$this->addForeignKey( OddsGGLeague::tableName() . '_SportId', OddsGGLeague::tableName(), 'SportId',
			OddsGGSport::tableName(), 'id', 'CASCADE', 'RESTRICT' );

		$this->createTable( OddsGGTournament::tableName(), [
			'id'         => $this->primaryKey(),
			'Name'       => $this->string( 255 )->notNull(),
			'LeagueId' => $this->integer( 11 )->notNull(),
			'Timestamp'  => $this->integer( 11 )->notNull(),
			'created_at' => $this->integer( 11 )->notNull(),
			'updated_at' => $this->integer( 11 )->notNull(),
		], $tableOptions );

		$this->addForeignKey( OddsGGTournament::tableName() . '_LeagueId', OddsGGTournament::tableName(), 'LeagueId',
			OddsGGLeague::tableName(), 'id', 'CASCADE', 'RESTRICT' );

		$this->createTable( OddsGGTeam::tableName(), [
			'id'         => $this->primaryKey(),
			'Name'       => $this->string( 255 )->notNull(),
			'created_at' => $this->integer( 11 )->notNull(),
			'updated_at' => $this->integer( 11 )->notNull(),
		], $tableOptions );

		$this->createTable( OddsGGMatch::tableName(), [
			'id'           => $this->primaryKey(),
			'SportId'      => $this->integer( 11 )->notNull(),
			'TournamentId' => $this->integer( 11 )->null(),
			'StartTime'    => $this->integer( 11 )->notNull(),
			'HomeTeamId'   => $this->integer( 11 )->notNull(),
			'AwayTeamId'   => $this->integer( 11 )->notNull(),
			'Score'        => $this->string( 255 )->notNull(),
			'Status'       => $this->integer( 11 )->null(),
			'StreamUrl'    => $this->text()->null(),
			'created_at'   => $this->integer( 11 )->notNull(),
			'updated_at'   => $this->integer( 11 )->notNull(),
		], $tableOptions );

		$this->addForeignKey( OddsGGMatch::tableName() . '_SportId', OddsGGMatch::tableName(), 'SportId',
			OddsGGSport::tableName(), 'id', 'CASCADE', 'RESTRICT' );

		$this->addForeignKey( OddsGGMatch::tableName() . '_TournamentId', OddsGGMatch::tableName(), 'TournamentId',
			OddsGGTournament::tableName(), 'id', 'CASCADE', 'RESTRICT' );

		$this->addForeignKey( OddsGGMatch::tableName() . '_HomeTeamId', OddsGGMatch::tableName(), 'HomeTeamId',
			OddsGGTeam::tableName(), 'id', 'CASCADE', 'RESTRICT' );

		$this->addForeignKey( OddsGGMatch::tableName() . '_AwayTeamId', OddsGGMatch::tableName(), 'AwayTeamId',
			OddsGGTeam::tableName(), 'id', 'CASCADE', 'RESTRICT' );

		$this->createTable( OddsGGMarket::tableName(), [
			'id'         => $this->primaryKey(),
			'Name'       => $this->string( 255 )->notNull(),
			'MatchId'    => $this->integer( 11 )->notNull(),
			'IsLive'     => $this->boolean()->notNull(),
			'Status'     => $this->integer( 11 )->notNull(),
			'Timestamp'  => $this->integer( 11 )->notNull(),
			'created_at' => $this->integer( 11 )->notNull(),
			'updated_at' => $this->integer( 11 )->notNull(),
		], $tableOptions );

		$this->addForeignKey( OddsGGMarket::tableName() . '_MatchId', OddsGGMarket::tableName(), 'MatchId',
			OddsGGMatch::tableName(), 'id', 'CASCADE', 'RESTRICT' );

		$this->createTable( OddsGGOdd::tableName(), [
			'id'         => $this->primaryKey(),
			'Name'       => $this->string( 255 )->notNull(),
			'Title'      => $this->string( 255 )->notNull(),
			'Value'      => $this->string( 255 )->notNull(),
			'IsActive'   => $this->boolean()->null(), // Premium only
			'Status'     => $this->integer( 11 )->null(), // Premium only
			'MatchId'    => $this->integer( 11 )->notNull(),
			'MarketId'   => $this->integer( 11 )->notNull(),
			'Timestamp'  => $this->integer( 11 )->notNull(),
			'created_at' => $this->integer( 11 )->notNull(),
			'updated_at' => $this->integer( 11 )->notNull(),
		], $tableOptions );

		$this->addForeignKey( OddsGGOdd::tableName() . '_MatchId', OddsGGOdd::tableName(), 'MatchId',
			OddsGGMatch::tableName(), 'id', 'CASCADE', 'RESTRICT' );

		$this->addForeignKey( OddsGGOdd::tableName() . '_MarketId', OddsGGOdd::tableName(), 'MarketId',
			OddsGGMarket::tableName(), 'id', 'CASCADE', 'RESTRICT' );
	}

	public function down() {

		$this->dropForeignKey( OddsGGOdd::tableName() . '_MarketId', OddsGGOdd::tableName() );
		$this->dropForeignKey( OddsGGOdd::tableName() . '_MatchId', OddsGGOdd::tableName() );
		$this->dropTable( OddsGGOdd::tableName() );

		$this->dropForeignKey( OddsGGMarket::tableName() . '_MatchId', OddsGGMarket::tableName() );
		$this->dropTable( OddsGGMarket::tableName() );

		$this->dropForeignKey( OddsGGMatch::tableName() . '_AwayTeamId', OddsGGMatch::tableName() );
		$this->dropForeignKey( OddsGGMatch::tableName() . '_HomeTeamId', OddsGGMatch::tableName() );
		$this->dropForeignKey( OddsGGMatch::tableName() . '_TournamentId', OddsGGMatch::tableName() );
		$this->dropForeignKey( OddsGGMatch::tableName() . '_SportId', OddsGGMatch::tableName() );
		$this->dropTable( OddsGGMatch::tableName() );

		$this->dropTable( OddsGGTeam::tableName() );

		$this->dropForeignKey( OddsGGTournament::tableName() . '_LeagueId', OddsGGTournament::tableName() );
		$this->dropTable( OddsGGTournament::tableName() );

		$this->dropForeignKey( OddsGGLeague::tableName() . '_SportId', OddsGGLeague::tableName() );
		$this->dropTable( OddsGGLeague::tableName() );

		$this->dropTable( OddsGGSport::tableName() );
	}
}
