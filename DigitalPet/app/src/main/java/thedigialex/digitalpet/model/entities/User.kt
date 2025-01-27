package thedigialex.digitalpet.model.entities

import android.util.Log

data class User(
    val id: Int,
    val name: String,
    val email: String,
    val tamer_level: Int,
    val tamer_exp: Int,
    var bits: Int,
    val score: Int,

    var eggs: List<DigitalMonster>? = null,
    var userDigitalMonster: List<UserDigitalMonster>? = null,
    var mainDigitalMonster: UserDigitalMonster? = null,
    var inventoryItems: List<InventoryItem>? = null,
    var trainingEquipments: List<UserTrainingEquipment>? = null,
    var itemsForSale: List<Item>? = null
) {
    fun findMainDigitalMonster(): UserDigitalMonster? {
        return userDigitalMonster?.firstOrNull { it.isMain == 1 }
    }

    fun getConsumableItems(): List<InventoryItem> {
        return inventoryItems?.filter { it.item.type == "Consumable" } ?: emptyList()
    }

    fun getUsedItem(innerMenuCycle: Int): InventoryItem {
        val consumableItems = getConsumableItems()
        val selectedItem = consumableItems[innerMenuCycle]
        selectedItem.quantity--
        val effects = selectedItem.item.effect.split("|")
        for (effect in effects) {
            val changes = effect.split(",")
            if (changes.size > 1) {
                val changeType = changes[0]
                val changeValue = changes[1].toInt()

                when (changeType) {
                    "H" -> {
                        mainDigitalMonster!!.hunger += changeValue
                        if (mainDigitalMonster!!.hunger > 4) {
                            mainDigitalMonster!!.hunger = 4
                        }
                    }
                    "S" -> {
                        mainDigitalMonster!!.strength += changeValue
                    }
                    "E" -> {
                        mainDigitalMonster!!.currentEvoPoints += changeValue
                        if (mainDigitalMonster!!.currentEvoPoints > mainDigitalMonster!!.digital_monster.requiredEvoPoints) {
                            mainDigitalMonster!!.currentEvoPoints = mainDigitalMonster!!.digital_monster.requiredEvoPoints
                        }
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
                "Strength" -> mainDigitalMonster!!.strength += userTrainingEquipment.level
                "Agility" -> mainDigitalMonster!!.agility += userTrainingEquipment.level
                "Defense" -> mainDigitalMonster!!.defense += userTrainingEquipment.level
                "Mind" -> mainDigitalMonster!!.mind += userTrainingEquipment.level
            }
            Log.d("training", userTrainingEquipment.toString())
        }
        if ((1..5).random() == 1) {
            mainDigitalMonster!!.hunger = maxOf(mainDigitalMonster!!.hunger - 1, 0)
        }
        if ((1..5).random() == 1) {
            mainDigitalMonster!!.clean = minOf(mainDigitalMonster!!.clean + 1, 4)
        }
    }

    fun getEquipmentByType(type: String = "Training"): List<UserTrainingEquipment> {
        return trainingEquipments?.filter { equipment ->
            when (type) {
                "Training" -> equipment.trainingEquipment.stat != "Cleaning" && equipment.trainingEquipment.stat != "Lighting"
                else -> equipment.trainingEquipment.stat == type
            }
        } ?: emptyList()
    }

    fun getEquippedItem(type: String): Item? {
        return inventoryItems?.firstOrNull { it.item.type == type && it.isEquipped == 1 }?.item
    }
}
