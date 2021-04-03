DROP DATABASE IF EXISTS GuildQuest;
CREATE DATABASE GuildQuest;
USE GuildQuest;

CREATE TABLE ACCOUNT
(   
	UserID					CHAR(15) NOT NULL,
	Username				CHAR(15) NOT NULL UNIQUE,
	Password				CHAR(20) NOT NULL UNIQUE,
	Email					CHAR(64) NOT NULL UNIQUE,
	Role					CHAR(5) CHECK(Role IN('Admin', 'Mod', 'User')),
	DateSignedUp			DATE NOT NULL,
	LeaderboardRanking		INTEGER,
	Balance					DECIMAL(7,2), 
	MoneySpent				DECIMAL(7,2),
	IsBanned				BOOL,
    INDEX user_id (UserID),
    PRIMARY KEY (UserID)
);

CREATE TABLE WORLD
(
	WorldID	 				CHAR(15) NOT NULL,
	WorldName				CHAR(64) NOT NULL DEFAULT 'New World',
	WorldSize				CHAR(6) CHECK(WorldSize IN('Small', 'Medium', 'Large')),
	MaxPlayerCapacity		INTEGER NOT NULL,
	CurrentPlayerCount		INTEGER NOT NULL,
	GameMode				CHAR(8) CHECK(GameMode IN('Survival', 'Creative')),
	WorldType    			CHAR(7) CHECK(WorldType IN('Private', 'Public')),
	InitialPlotPrices		INTEGER NOT NULL DEFAULT 0,
    INDEX world_id(WorldID),
    PRIMARY KEY (WorldID)
);

CREATE TABLE QUEST
(
	QuestID 				CHAR(15) NOT NULL,
	QuestName				CHAR(30) NOT NULL,
	QuestDescription		TEXT NOT NULL,
	CoinsGained				INTEGER NOT NULL,
	ExperienceGained		INTEGER NOT NULL,
	TimeLimit				TIME,
	MinLevel				INTEGER,
    INDEX quest_id (QuestID),
    PRIMARY KEY (QuestID)
);

CREATE TABLE GUILD
(
	GuildID 				CHAR(15) NOT NULL,
	GuildName				CHAR(30) NOT NULL,
	GuildMemberCount		INTEGER NOT NULL,
	GuildLevel				INTEGER NOT NULL,
    INDEX guild_id (GuildID),
    PRIMARY KEY (GuildID)
);

CREATE TABLE PLAYER
(
	PlayerID 				CHAR(15) NOT NULL,
	PlayerName				CHAR(15) NOT NULL,
	Account					CHAR(15),
	FOREIGN KEY (Account) REFERENCES ACCOUNT(UserID) ON DELETE SET NULL,
	DateLastLogged			DATE NOT NULL,
	Experience				INTEGER DEFAULT 0,
	Coins					INTEGER DEFAULT 0,
	Attack					INTEGER DEFAULT 0,
	Defence					INTEGER DEFAULT 0,
	Health					INTEGER DEFAULT 0,
	Level					INTEGER DEFAULT 0,
	TitleRank				CHAR(10) CHECK(TitleRank IN(NULL,'Donor', 'SuperDonor')),
	Guild					CHAR(15),
    FOREIGN KEY (Guild) REFERENCES GUILD(GuildID) ON DELETE SET NULL,
	GuildPosition			CHAR(6) CHECK(GuildPosition IN('Leader', 'Elder', 'Member', NULL)),
	World					CHAR(15),
    FOREIGN KEY (World) REFERENCES WORLD(WorldID) ON DELETE SET NULL,
	Sword					CHAR(30),
	Armor					CHAR(30),
	Wood					INTEGER DEFAULT 0,
	Fish					INTEGER DEFAULT 0,
	Food					INTEGER DEFAULT 0,
	Diamonds				INTEGER DEFAULT 0,
    INDEX player_ID (PlayerID),
    PRIMARY KEY (PlayerID)
);

/*Creating mission table to define the many to many relationship between players and quests*/
CREATE TABLE MISSION
(
	PlayerID 				CHAR(15) NOT NULL,
    QuestID 				CHAR(15) NOT NULL,
    FOREIGN KEY (PlayerID) REFERENCES PLAYER(PlayerID) ON DELETE CASCADE,
    FOREIGN KEY (QuestID) REFERENCES QUEST(QuestID) ON DELETE CASCADE,
    INDEX player_id (PlayerID),
    KEY (PlayerID, QuestID)
);

CREATE TABLE PLOT
(
	PlotID 					CHAR(15) NOT NULL,
	World					CHAR(15) NOT NULL,
    FOREIGN KEY (World) REFERENCES WORLD(WorldID) ON DELETE CASCADE,
	Owner					CHAR(15),
    FOREIGN KEY (Owner) REFERENCES PLAYER(PlayerID) ON DELETE SET NULL,
	DailyUpkeep				DECIMAL(4,2),
	PvP						BOOL DEFAULT FALSE,
	PermissionType 			CHAR(8) CHECK(PermissionType IN('Resident', 'Ally', 'Outsider')),
    INDEX plot_id (PlotID),
    PRIMARY KEY (PlotID)
);

COMMIT;