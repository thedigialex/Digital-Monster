package thedigialex.digitalpet.model.entities

data class User(
    val id: Int,
    val name: String,
    val email: String,
    val emailVerifiedAt: String?,
    val role: String,
    val tamerLevel: Int,
    val tamerExp: Int,
    val bits: Int,
    val score: Int,
    var mainDigitalMonster: UserDigitalMonster? = null,
    var inventoryItems: List<InventoryItem>? = null,
    var trainingEquipments: List<UserTrainingEquipment>? = null,
    var itemsForSale: List<Item>? = null
) {
    fun getConsumableItems(): List<InventoryItem> {
        return inventoryItems?.filter { it.item.type == "consumable" } ?: emptyList()
    }

    fun getUsedItem(innerMenuCycle: Int): InventoryItem {
        val consumableItems = getConsumableItems()
        val selectedItem = consumableItems[innerMenuCycle]
        selectedItem.quantity--
        val effects = selectedItem.item.effect.split("|")
        for (effect in effects) {
            val changes = effect.split(",")
            for (change in changes) {
                when (change[0]) {
                    'H' -> {
                        mainDigitalMonster!!.hunger ++
                        if (mainDigitalMonster!!.hunger > 4) {
                            mainDigitalMonster!!.hunger = 4
                        }
                    }
                    'S' -> {
                        mainDigitalMonster!!.strength += change[1].toString().toInt()
                    }
                    // Add more cases as needed
                }
            }
        }

        inventoryItems = inventoryItems?.filter { it.quantity > 0 }
        return selectedItem
    }

    fun useTrainingEquipment(userTrainingEquipment: UserTrainingEquipment) {
        mainDigitalMonster!!.exercise = minOf(mainDigitalMonster!!.exercise + 1, 4)
        if(mainDigitalMonster!!.trainings < mainDigitalMonster!!.maxTrainings) {
            mainDigitalMonster!!.trainings += 1
            when (userTrainingEquipment.trainingEquipment.stat) {
                "strength" -> mainDigitalMonster!!.strength += userTrainingEquipment.statIncrease
                "agility" -> mainDigitalMonster!!.agility += userTrainingEquipment.statIncrease
                "defense" -> mainDigitalMonster!!.defense += userTrainingEquipment.statIncrease
                "mind" -> mainDigitalMonster!!.mind += userTrainingEquipment.statIncrease
            }
        }
    }

    fun getTrainingEquipment(): List<UserTrainingEquipment> {
        return trainingEquipments?.filter { it.trainingEquipment.stat != "cleaning" } ?: emptyList()
    }

    fun getCleaningEquipment(): UserTrainingEquipment? {
        return trainingEquipments?.firstOrNull { it.trainingEquipment.stat == "cleaning" }
    }
}
